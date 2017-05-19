<?php
namespace Home\Model;

use Think\Model;

class DayratecontractModel extends Model
{
    protected $trueTableName = 'dayratecontract';

    // 查询当前用户是否已经和上级签署了日工资契约
    public function isHaveDayratecontrace()
    {
        if(session("SESSION_ROLE")!=3)
        {
            $secondparty = session("SESSION_ID");
            $res = $this->where(array("secondparty"=>$secondparty))->find();
            if(!empty($res))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    // 查询日工资契约的状态
    public function SearchContractStatus()
    {
        $data = $this->data;
        $firstparty = $data['firstparty'];
        $secondparty = $data['secondparty'];

        $xReturn = $this->where(array('firstparty'=>$firstparty,'secondparty'=>$secondparty))->field('id,secondparty,originator,status')->find();
        if(!empty($xReturn))
            return json_encode($xReturn);
        else
            return json_encode(array("status"=>0));
    }

    // 添加日工资契约
    public function AddContractItem()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $addItem = $this->data;
        $addItem = $addItem['data'];
        $len = count($addItem['sales']);
        $firstparty = session("SESSION_ID");
        $firstpartypath = session("SESSION_PATH");
        $secondparty = $_xDe->decode($addItem['u']);

        // 查询签约的用户是否属于自己的直接下级
        $uReturn = M("user")->where(array("id"=>$secondparty))->field("parent_id,parent_path,group_id")->find();
        $secondpartypath = $uReturn['parent_path'];
        // 检查签约用户的返点状态
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $key = md5("userbonus".$secondparty);
        if($redisObj->exists($key))
        {
            $userBonusInfo = json_decode($redisObj->_get($key), true);
        }
        else
        {
            $userBonusInfo = M("user_bonus")->where(array('userid'=>$secondparty))->find();
            $redisObj->_set($key,json_encode($userBonusInfo));
        }
        $bonusLevel = $userBonusInfo['bonus_level'];
        if($firstparty!=$uReturn['parent_id'] || $uReturn['group_id']!=4)
        {
            return "0-签订契约日工资失败";
            exit();
        }

        // 查询待签约的用户与他的下级最高签约标准
        $maxreward = $this->where(array("firstparty"=>$secondparty))->max('reward');
        $rewardarr = array();
        $isSuccess = true;
        for($i=0; $i<$len; $i++)
        {
            $reward = $addItem['reward'][$i];
            if($reward<$maxreward)
            {
                $isSuccess = false;
            }
        }

        if(!$isSuccess)
        {
            return "0-签订标准需遵循（等于或高于）正在签约用户下级中最高的规则";
            exit();
        }


        for($i=0; $i<$len; $i++)
        {
            $dailysales = $addItem['sales'][$i];
            $reward = $addItem['reward'][$i];
            $activepeople = $addItem['activepeople'][$i];
            $activebet = $addItem['activebet'][$i];
            $activepeople = ($activepeople=="") ? 0 : $activepeople;
            $activebet = ($activebet=="") ? 0 : $activebet;
            $starttime = time();
            $status = 1;
            $InsertData = array("firstparty"=>$firstparty,"firstpartypath"=>$firstpartypath,"secondparty"=>$secondparty,
            "secondpartypath"=>$secondpartypath,"dailysales"=>$dailysales,"reward"=>$reward,"activepeople"=>$activepeople,"activebet"=>$activebet,"starttime"=>$starttime,
            "status"=>$status);
            try
            {
                $this->add($InsertData);
            }
            catch (Exception $ex)
            {
                return "0-签订契约日工资失败";
                exit();
            }
        }
        return "1-等待下级进行签约";
        exit();
        $msg = "您的上级对你发起了一份契约日工资，请查看";
        $send = $msg."-12-".time();
        $content = array('type'=>"inbox","to"=>$secondparty,'content'=>$send);
        $result = self::sendContractWebSocketMsg($content);
        return true;
    }

    // 查询签约中的契约
    public function SearchContracting()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $addItem = $this->data;
        $addItem = $addItem['data'];
        $id = $_xDe->decode($addItem['contractId']);
        $xReturn = $this->where(array("secondparty"=>$id,"status"=>1))->field('firstparty,secondpartypath,dailysales,reward,activepeople,activebet')->select();
        $secondpartypath = explode(",", $xReturn[0]['secondpartypath']);
        $secondParentId = array_pop($secondpartypath);
        // 验证这条数据的合法性
        if($xReturn[0]['firstparty']!=session("SESSION_ID") || $secondParentId!=session("SESSION_ID"))
        {
            return null;
            exit();
        }
        return json_encode($xReturn);
    }

    // 查询签约成功的契约
    public function SearchContractend()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $addItem = $this->data;
        $addItem = $addItem['data'];
        $id = $_xDe->decode($addItem['contractId']);
        $xReturn = $this->where("secondparty=".$id." and (status=2 or status=3)")->field('id,firstparty,secondpartypath,dailysales,reward,activepeople,activebet,status')->select();
        $secondpartypath = explode(",", $xReturn[0]['secondpartypath']);
        $secondParentId = array_pop($secondpartypath);
        // 验证这条数据的合法性
        if($xReturn[0]['firstparty']!=session("SESSION_ID") || $secondParentId!=session("SESSION_ID"))
        {
            return null;
            exit();
        }
        $result = array();
        foreach($xReturn as $item)
        {
            $item["id"] = $_xDe->encode($item["id"]);
            $result[] = $item;
        }
        return json_encode($result);
    }

    // 查询等待解除契
    public function SearchDisContracting()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $addItem = $this->data;
        $addItem = $addItem['data'];
        $id = $_xDe->decode($addItem['contractId']);
        $xReturn = $this->where(array("id"=>$id,"status"=>3))->field('id,firstparty,secondpartypath,originator,dailysales,reward,activepeople,activebet')->select();
        $secondpartypath = explode(",", $xReturn[0]['secondpartypath']);
        $secondParentId = array_pop($secondpartypath);
        // 验证这条数据的合法性
        if($xReturn[0]['firstparty']!=session("SESSION_ID") || $secondParentId!=session("SESSION_ID"))
        {
            return null;
            exit();
        }
        $result = array();
        foreach($xReturn as $item)
        {
            $item["id"] = $_xDe->encode($item["id"]);
            $result[] = $item;
        }
        return json_encode($result);
    }

    // 获取当前的契约
    public function GetContract()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $secondparty = session("SESSION_ID");
        $xReturn = $this->where(array("secondparty"=>$secondparty))->field('id,dailysales,reward,activepeople,activebet,originator,status')->select();
        $return = array();
        if(empty($xReturn)) return json_encode($return);
        foreach($xReturn as $item)
        {
            $item['id'] = $_xDe->encode($item['id']);
            if($item['status']==1)
            {
                $item['desc'] = "<font style='color:#ee6528'>签约进行中...</font>";
                $item['operate'] = '<a class="layui-btn" data-method="agree_dayrate_contract" data-field="'.$item['id'].'">同意签约</a>';
            }
            else if($item['status']==2)
            {
                $item['desc'] = "<font style='color:green'>签约成功</font>";
                $item['operate'] = '<a class="layui-btn" data-method="relieve_dayrate_contract" data-field="'.$item['id'].'">申请解约</a>';
            }
            else if($item['status']==3)
            {
                $item['desc'] = "<font style='color:#ee6528'>解除签约中...</font>";
                if($item['originator']==$secondparty)
                    $item['operate'] = '<a class="layui-btn" data-field="'.$item['id'].'" data-method="agreeDisDayrateContract">同意解约</a>';
                else
                    $item['operate'] = '<a class="layui-btn">禁止操作</a>';
            }
            $return[] = $item;
        }
        return json_encode($return);
    }

    // 下级与上级进行签约
    public function agreeContractHandler()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $data = $this->data;
        $id = $_xDe->decode($data['data']['id']);
        $list = $this->where(array("id"=>$id))->find();
        $secondparty = $list['secondparty'];
        if($secondparty!=session("SESSION_ID") || empty($list) || session("SESSION_PARENTID")!=$list['firstparty'] || session("SESSION_ROLE")!=4)
        {
            return false;
            exit();
        }
        if($this->where(array("id"=>$id))->save(array("status"=>2)))
        {
            $username = M("user")->where(array("id"=>$secondparty))->getField("username");
            $msg = "您的下级".$username."同意了一份契约日工资，请查看";
            $send = $msg."-13-".time();
            $content = array('type'=>"inbox","to"=>$list['firstparty'],'content'=>$send);
            $result = self::sendContractWebSocketMsg($content);
            return true;
        }
        else
        {
            return false;
        }
    }

    // 申请解除契约[下级向上级申请解除契约]
    public function releaseContractHandler()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $data = $this->data;

        // 查看是否有未处理的解约
        $isHaveDisContract = $this->where(array("firstparty"=>session("SESSION_PARENTID"),"status"=>3))->count();
        if($isHaveDisContract>0)
        {
            return false;
            exit();
        }

        $id = $_xDe->decode($data['data']['id']);
        $list = $this->where(array("id"=>$id))->find();
        $secondparty = $list['secondparty'];
        if($secondparty!=session("SESSION_ID") || empty($list) || session("SESSION_PARENTID")!=$list['firstparty'] || session("SESSION_ROLE")!=4)
        {
            return false;
            exit();
        }
        if($this->where(array("id"=>$id))->save(array("status"=>3,"originator"=>session("SESSION_PARENTID"))))
        {
            $username = M("user")->where(array("id"=>$secondparty))->getField("username");
            $msg = "您的下级".$username."申请解除一份契约日工资，请查看";
            $send = $msg."-13-".time();
            $content = array('type'=>"inbox","to"=>$list['firstparty'],'content'=>$send);
            $result = self::sendContractWebSocketMsg($content);
            return true;
        }
        else
        {
            return false;
        }
    }

    // 同意解除契约【上级同意下级解除契约】
    public function agreeReleaseContractHandler()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $data = $this->data;
        $id = $_xDe->decode($data['data']['id']);
        $list = $this->where(array("id"=>$id))->find();
        if(session("SESSION_ID")!=$list['originator'] || empty($list))
        {
            return false;
            exit();
        }
        $this->where(array("id"=>$id))->delete();
        $msg = "您的上级已同意解除契约日工资";
        $send = $msg."-13-".time();
        $content = array('type'=>"inbox","to"=>$list['secondparty'],'content'=>$send);
        $result = self::sendContractWebSocketMsg($content);
        return true;
    }

    // 申请解除契约[上级向下级申请解除契约]
    public function TopToDownreleaseContractHandler()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $data = $this->data;

        $id = $_xDe->decode($data['data']['id']);
        $list = $this->where(array("id"=>$id))->find();

        // 查看是否有未处理的解约
        $isHaveDisContract = $this->where(array("secondparty"=>$list['secondparty'],"status"=>3))->count();
        if($isHaveDisContract>0)
        {
            return false;
            exit();
        }

        $secondpartypath = $list['secondpartypath'];
        $secondpartypathArr = explode(",", $secondpartypath);
        $pid = array_pop($secondpartypathArr);
        if($pid!=session("SESSION_ID") || empty($list))
        {
            return false;
            exit();
        }
        if($this->where(array("id"=>$id))->save(array("status"=>3,"originator"=>$list['secondparty'])))
        {
            $msg = "您的上级申请解除一份契约日工资，请查看";
            $send = $msg."-13-".time();
            $content = array('type'=>"inbox","to"=>$list['secondparty'],'content'=>$send);
            $result = self::sendContractWebSocketMsg($content);
            return true;
        }
        else
        {
            return false;
        }
    }

    // 同意解除契约【下级同意上级解除契约】
    public function DownToTopagreeReleaseContractHandler()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $data = $this->data;
        $id = $_xDe->decode($data['data']['id']);
        $list = $this->where(array("id"=>$id))->find();
        if(session("SESSION_ID")!=$list['originator'] || empty($list))
        {
            return false;
            exit();
        }
        $username = M("user")->where(array("id"=>$list['secondparty']))->getField("username");
        $this->where(array("id"=>$id))->delete();
        $msg = "您的下级".$username."已同意解除契约日工资";
        $send = $msg."-13-".time();
        $content = array('type'=>"inbox","to"=>$list['firstparty'],'content'=>$send);
        $result = self::sendContractWebSocketMsg($content);
        return true;
    }

    // 签署契约推送消息
    public static function sendContractWebSocketMsg($content)
    {
        $push_api_url = "http://".C("TUISONG_HOST").":2121/";
        $post_data = $content;
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }
}




?>
