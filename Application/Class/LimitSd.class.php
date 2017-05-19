<?php

class LimitSd{
	/************************************************************************************************
		参数说明：
		$uid   用户ID
		$unum：购买的号码
		$lid   彩票ID
		$buy_type  玩法名称
		$mul        倍数
		$yuan     圆角模式标志，1为角模式
		$lim_num    限制购买的总中奖金额

	***************************************************************************************************/
	static function lim_dispatch($uid,$unum,$lid,$buy_type,$mul,$yuan,$bonus_type){

            $lim_3d = array();

	        import("Class.RedisObject");
	        $redisObj = new \RedisObject();
	        $redisObj->_setOption();
	        $f3dSwitchSetModel = M("f3d_switch_set");
	        if($redisObj->exists("F3DSWITCHSET")){
	            $f3dswitch = $redisObj->_get("F3DSWITCHSET");
	            foreach ($f3dswitch as $key => $value) {
	                $lim_3d[$value["playway"]]  = $value["money"];
	            }
	        } else {
	            $f3dswitch = $f3dSwitchSetModel->where(true)->select();
	            $redisObj->_set("F3DSWITCHSET",$f3dswitch);
	            foreach ($f3dswitch as $key => $value) {
	                $lim_3d[$value["playway"]]  = $value["money"];
	            }
	        }

            $lim_num = $lim_3d[$buy_type];
			switch($buy_type){
			
				case '直选复式':
					
					self::lim_zhxfsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '直选单式':
					self::lim_zhxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
			
				case '直选和值':
					self::lim_zhxhzh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;	
					
				case '组三':
					self::lim_zs($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '组六':
					self::lim_zl($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '混合组选':
					self::lim_hhzx($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '组选和值':
					self::lim_zxhzh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '后二直选复式':
				case '前二直选复式':
					self::lim_qher_zhxfsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '后二直选单式':
				case '前二直选单式':
					self::lim_qher_zhxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '后二组选复式':
				case '前二组选复式':
					self::lim_qher_zxfsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '后二组选单式':
				case '前二组选单式':
					self::lim_qher_zxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '后二大小单双':
				case '前二大小单双':

					self::lim_qher_dxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '三星大小单双':

					self::lim_sx_dxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '定位胆':

					self::lim_dwd($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '一码不定位':

					self::lim_ym_bdw($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
				case '二码不定位':

					self::lim_erm_bdw($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type);
					break;
			}
	}





			//直选复式

			function lim_zhxfsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){

                $model=self::getmodel($lid);

				$unum_arr=explode(",",$unum);

				$hun_count=strlen(trim($unum_arr[0]));
				$ten_count=strlen(trim($unum_arr[1]));
				$bit_count=strlen(trim($unum_arr[2]));

				$user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

				$hun_arr=array();
				$ten_arr=array();
				$bit_arr=array();
				for($i=0;$i<$hun_count;$i++){
					$hun_arr[$i]=$unum_arr[0][$i];
				
				}
				$hun_str=implode(',',$hun_arr);
				for($j=0;$j<$ten_count;$j++){
				
					$ten_arr[$j]=$unum_arr[1][$j];
				
				}
				$ten_str=implode(',',$ten_arr);
				for($l=0;$l<$bit_count;$l++){
					$bit_arr[$l]=$unum_arr[2][$l];
					
				}
				$bit_str=implode(',',$bit_arr);


							self::getMaxbonus($model,"FIND_IN_SET(bit_num,'$bit_str') AND FIND_IN_SET(ten_num,'$ten_str') AND FIND_IN_SET(hundred_num,'$hun_str')",$user_bonus,$lim_num);



						
							
							$model->where("FIND_IN_SET(bit_num,'$bit_str') AND FIND_IN_SET(ten_num,'$ten_str') AND FIND_IN_SET(hundred_num,'$hun_str')")->setInc('cur_total_money',$user_bonus);
						
	
				

			
			}



			//前后二直选复式
			function lim_qher_zhxfsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
			
				$model=self::getmodel($lid);
				$unum_arr=explode(",",$unum);
				
				$ten_count=strlen(trim($unum_arr[0]));
				$bit_count=strlen(trim($unum_arr[1]));
                $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);





				$ten_arr=array();
				$bit_arr=array();

				for($j=0;$j<$ten_count;$j++){
				
					$ten_arr[$j]=$unum_arr[0][$j];
				
				}
				$ten_str=implode(',',$ten_arr);
				for($l=0;$l<$bit_count;$l++){
					$bit_arr[$l]=$unum_arr[1][$l];
					
				}
				$bit_str=implode(',',$bit_arr);

				if(strpos($buy_type,'后二')!==false){

					self::getMaxbonus($model," FIND_IN_SET(bit_num,'$bit_str') AND FIND_IN_SET(ten_num,'$ten_str')",$user_bonus,$lim_num);


				}elseif(strpos($buy_type,'前二')!==false){
					self::getMaxbonus($model,"find_in_set(ten_num,'$bit_str') AND find_in_set(hundred_num,'$ten_str')",$user_bonus,$lim_num);
				
				
				}				


							if(strpos($buy_type,'后二')!==false){

								$model->where(" FIND_IN_SET(bit_num,'$bit_str') AND FIND_IN_SET(ten_num,'$ten_str')")->setInc('cur_total_money',$user_bonus);

							}elseif(strpos($buy_type,'前二')!==false){

								$model->where("find_in_set(ten_num,'$bit_str') AND find_in_set(hundred_num,'$ten_str')")->setInc('cur_total_money',$user_bonus);							
							
							}
			

					










							
			
			}




			//直选单式

			function lim_zhxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
				$model=self::getmodel($lid);
				$unum_arr=explode(",",$unum);
				$arr_count=count($unum_arr);
                $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

				for($i=0;$i<$arr_count;$i++){

						
							$str=$unum_arr[$i][0].$unum_arr[$i][1].$unum_arr[$i][2];

							self::getMaxbonus($model,"bit_num={$unum_arr[$i][0]} AND ten_num={$unum_arr[$i][1]} AND hundred_num={$unum_arr[$i][2]}",$user_bonus,$lim_num);

						
		
				
				}
				
				for($i=0;$i<$arr_count;$i++){

						
							$str=$unum_arr[$i][0].$unum_arr[$i][1].$unum_arr[$i][2];
							$model->where("bit_num={$unum_arr[$i][0]} AND ten_num={$unum_arr[$i][1]} AND hundred_num={$unum_arr[$i][2]}")->setInc('cur_total_money',$user_bonus);
						
		
				
				}	

			
			}



			//前后二直选单式

			function lim_qher_zhxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
				$model=self::getmodel($lid);
				$unum_arr=explode(",",$unum);

				$arr_count=count($unum_arr);
                $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

				if(strpos($buy_type,'前二')!==false){
				
					$where1='ten_num=';
					$where2='hundred_num=';
				
				}else{
				
					$where1='bit_num=';
					$where2='ten_num=';				
				}

				for($i=0;$i<$arr_count;$i++){

						self::getMaxbonus($model,$where1.$unum_arr[$i][1].' AND '.$where2.$unum_arr[$i][0],$user_bonus,$lim_num);
									
				}
				

				for($i=0;$i<$arr_count;$i++){

						
						
							$model->where($where1.$unum_arr[$i][1].' AND '.$where2.$unum_arr[$i][0])->setInc('cur_total_money',$user_bonus);
						
		
				
				}

			
			}

			//直选和值

			function lim_zhxhzh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
				$model=self::getmodel($lid);
				$unum_arr=trim($unum);
				//$arr_count=count($unum_arr);
                $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

				

				

						
						self::getMaxbonus($model,"FIND_IN_SET(sum1,'$unum_arr')",$user_bonus,$lim_num);	
							//$model->where('sum1='.$unum_arr[$i])->setInc('cur_total_money',$user_bonus);
						
		
				
				
				

				

						
						
							$model->where("FIND_IN_SET(sum1,'$unum_arr')")->setInc('cur_total_money',$user_bonus);
						
		
				
				

			
			}

		//组选和值
		function lim_zxhzh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
		
				$model=self::getmodel($lid);
				$unum_arr=trim($unum);
				
				$_bonusTypeArr = explode("|",$bonus_type);
                $bonus_arr = explode("｜",$_bonusTypeArr[0]);
                $zs_bonus = $bonus_arr[0]*$mul;
                $zl_bonus = $bonus_arr[1]*$mul;

                if($yuan==1){
                    $zs_bonus = $bonus_arr[0]*$mul/10;
                    $zl_bonus = $bonus_arr[1]*$mul/10;
                }elseif($yuan==2){
                    $zs_bonus = $bonus_arr[0]*$mul/100;
                    $zl_bonus = $bonus_arr[1]*$mul/100;
                }elseif($yuan==3){
                    $zs_bonus = $bonus_arr[0]*$mul/1000;
                    $zl_bonus = $bonus_arr[1]*$mul/1000;
                }

				self::getMaxbonus($model,'find_in_set(sum2,"'.$unum_arr.'")' .' AND is_zs=1',$zs_bonus,$lim_num);
				self::getMaxbonus($model,'find_in_set(sum2,"'.$unum_arr.'")' .' AND is_zs=2',$zl_bonus,$lim_num);	
				$model->where('find_in_set(sum2,"'.$unum_arr.'")' .' AND is_zs=1')->setInc('cur_total_money',$zs_bonus);
				$model->where('find_in_set(sum2,"'.$unum_arr.'")' .' AND is_zs=2')->setInc('cur_total_money',$zl_bonus);
		}

		//组三
		function lim_zs($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				$unum_arr=trim($unum);
				$str_len=strlen($unum_arr);
				$unum=array();
				for($i=0;$i<$str_len;$i++){
				
					$unum[$i]=$unum_arr[$i];
				
				}
                $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				$unum_str=implode(',',$unum);

				self::getMaxbonus($model,"find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=1",$user_bonus,$lim_num);

				$model->where("find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=1")->setInc('cur_total_money',$user_bonus);
				

				
		
		
		}

		//组六
		function lim_zl($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				$unum_arr=trim($unum);
				$str_len=strlen($unum_arr);
				$unum=array();
				for($i=0;$i<$str_len;$i++){
				
					$unum[$i]=$unum_arr[$i];
				
				}
                $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				$unum_str=implode(',',$unum);

				self::getMaxbonus($model,"find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=2",$user_bonus,$lim_num);

				$model->where("find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=2")->setInc('cur_total_money',$user_bonus);
				

				
		
		
		}

		//混合组选

		function lim_hhzx($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				$unum_arr=explode(",",$unum);
				$arr_count=count($unum_arr);

                $_bonusTypeArr = explode("|",$bonus_type);
                $bonus_arr = explode("｜",$_bonusTypeArr[0]);
                $zs_bonus = $bonus_arr[0]*$mul;
                $zl_bonus = $bonus_arr[1]*$mul;

                if($yuan==1){
                    $zs_bonus = $bonus_arr[0]*$mul/10;
                    $zl_bonus = $bonus_arr[1]*$mul/10;
                }elseif($yuan==2){
                    $zs_bonus = $bonus_arr[0]*$mul/100;
                    $zl_bonus = $bonus_arr[1]*$mul/100;
                }elseif($yuan==3){
                    $zs_bonus = $bonus_arr[0]*$mul/1000;
                    $zl_bonus = $bonus_arr[1]*$mul/1000;
                }

				for($i=0;$i<$arr_count;$i++){
					$unum_str=$unum_arr[$i][0].",".$unum_arr[$i][1].",".$unum_arr[$i][2];


					self::getMaxbonus($model,"find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=2",$zl_bonus,$lim_num);
					
					self::getMaxbonus($model,"find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=1",$zs_bonus,$lim_num);
				
				
				}
				for($i=0;$i<$arr_count;$i++){
					$unum_str=$unum_arr[$i][0].",".$unum_arr[$i][1].",".$unum_arr[$i][2];
					$model->where("find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=2")->setInc('cur_total_money',$zl_bonus);
				
					$model->where("find_in_set(`bit_num`,'$unum_str') AND find_in_set(`ten_num`,'$unum_str') AND find_in_set(`hundred_num`,'$unum_str') AND is_zs=1")->setInc('cur_total_money',$zs_bonus);				
				}
		
		}



		//前后二组选


		function lim_qher_zxfsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				$unum_len=strlen($unum);

				$temp_arr=array();
				
				for($i=0;$i<$unum_len;$i++){
				
					$temp_arr[$i]=$unum[$i];
				
				
				}

				$unum_str=implode(',',$temp_arr);


            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

				if(strpos($buy_type,'前二')!==false){
				
					$where1="FIND_IN_SET (ten_num,'$unum_str') ";
					$where2=" FIND_IN_SET (hundred_num,'$unum_str')";
					$where3=" ten_num!=hundred_num ";
				
				}else{
				
					$where1="FIND_IN_SET (bit_num,'$unum_str')";
					$where2=" FIND_IN_SET (ten_num,'$unum_str')";	
					$where3=" ten_num!=bit_num ";
				}
				self::getMaxbonus($model,$where1.' AND '.$where2 .' AND '.$where3,$user_bonus,$lim_num);	
				$model->where($where1.' AND '.$where2 .' AND '.$where3)->setInc('cur_total_money',$user_bonus);		
		
		}

		//前后二组选单式

		function lim_qher_zxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				
				$unum_arr=explode(',',$unum);

				$unum_len=count($unum_arr);

            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				for($i=0;$i<$unum_len;$i++){
				
					$unum_str=$unum_arr[$i][0].','.$unum_arr[$i][1];
					if(strpos($buy_type,'前二')!==false){
						$where="FIND_IN_SET (ten_num,'$unum_str') AND FIND_IN_SET (hundred_num,'$unum_str') AND ten_num!=hundred_num";
						self::getMaxbonus($model,$where,$user_bonus,$lim_num);
						$model->where(" $where ")->setInc('cur_total_money',$user_bonus);	
						
					}else{
						$where="FIND_IN_SET (bit_num,'$unum_str') AND FIND_IN_SET (ten_num,'$unum_str')  AND ten_num!=bit_num";
						self::getMaxbonus($model,$where,$user_bonus,$lim_num);					
						$model->where(" $where ")->setInc('cur_total_money',$user_bonus);						
					}				
				
				}

		
		
		
		}

		//三星大小单双

		function lim_sx_dxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				
				$unum_arr=explode(',',$unum);
				
				$bit_unum_len=mb_strlen($unum_arr[2], "UTF-8");
				$ten_unum_len=mb_strlen($unum_arr[1], "UTF-8");
				$hun_unum_len=mb_strlen($unum_arr[0], "UTF-8");
            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

				for($i=0;$i<$bit_unum_len;$i++){

					for($j=0;$j<$ten_unum_len;$j++){

						for($l=0;$l<$hun_unum_len;$l++){
							
								
							$bitmb_substr=mb_substr($unum_arr[2],$i,1,"UTF-8");
							$tenmb_substr=mb_substr($unum_arr[1],$j,1,"UTF-8");
							$hunmb_substr=mb_substr($unum_arr[0],$l,1,"UTF-8");
							
							switch($bitmb_substr){
								case '大':
									$bit_sql='bit_num>=5';
									break;
								case '小':
									$bit_sql='bit_num<5';
									break;							
								case '双':
									$bit_sql='bit_num%2=0';
									break;
								case '单':
									$bit_sql='bit_num%2=1';
									break;								
							}


							switch($tenmb_substr){
								case '大':
									$ten_sql='ten_num>=5';
									break;
								case '小':
									$ten_sql='ten_num<5';
									break;							
								case '双':
									$ten_sql='ten_num%2=0';
									break;
								case '单':
									$ten_sql='ten_num%2=1';
									break;								
							}


							switch($hunmb_substr){
								case '大':
									$hun_sql='hundred_num>=5';
									break;
								case '小':
									$hun_sql='hundred_num<5';
									break;							
								case '双':
									$hun_sql='hundred_num%2=0';
									break;
								case '单':
									$hun_sql='hundred_num%2=1';
									break;								
							}
							self::getMaxbonus($model,"$bit_sql AND $ten_sql AND $hun_sql",$user_bonus,$lim_num);
							$model->where("$bit_sql AND $ten_sql AND $hun_sql")->setInc('cur_total_money',$user_bonus);								
						}					
					}				
				}
		
		
		}



		// 前后二大小单双

		function lim_qher_dxdsh($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
				
				$unum_arr=explode(',',$unum);
				
				
				$ten_unum_len=mb_strlen($unum_arr[1], "UTF-8");
				$hun_unum_len=mb_strlen($unum_arr[0], "UTF-8");
            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				for($i=0;$i<$ten_unum_len;$i++){
					for($j=0;$j<$hun_unum_len;$j++){
						
							
								
							$tenmb_substr=mb_substr($unum_arr[1],$i,1,"UTF-8");
							$hunmb_substr=mb_substr($unum_arr[0],$j,1,"UTF-8");

							

							if(strpos($buy_type,'前二')!==false){
							
								$sql_field='ten_num';
								$sql_field1='hundred_num';

						}else{
							
								$sql_field='bit_num';
								$sql_field1='ten_num';							
							}



							switch($tenmb_substr){
								case '大':
									$ten_sql="$sql_field>=5";
									break;
								case '小':
									$ten_sql="$sql_field<5";
									break;							
								case '双':
									$ten_sql='$sql_field%2=0';
									break;
								case '单':
									$ten_sql="$sql_field%2=1";
									break;								
							}


							switch($hunmb_substr){
								case '大':
									$hun_sql="$sql_field1>=5";
									break;
								case '小':
									$hun_sql="$sql_field1<5";
									break;							
								case '双':
									$hun_sql="$sql_field1%2=0";
									break;
								case '单':
									$hun_sql="$sql_field1%2=1";
									break;								
							}
							self::getMaxbonus($model," $ten_sql AND $hun_sql",$user_bonus,$lim_num);
							$model->where(" $ten_sql AND $hun_sql")->setInc('cur_total_money',$user_bonus);								
						}					

				}
		
		
		}

		//定位胆
		function lim_dwd($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
				$model=self::getmodel($lid);
				
				$unum_arr=explode(',',$unum);
				
				$where='';
            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				if(empty($unum_arr[2])==false){
					$temp_str=trim($unum_arr[2]);
					$temp_len=strlen($temp_str);
					$temp_arr=array();
					for($i=0;$i<$temp_len;$i++){
					
						$temp_arr[$i]=$temp_str[$i];
					
					}
					$new_str=implode(',',$temp_arr);
					$where.=" FIND_IN_SET (bit_num,'$new_str') OR";
				
				}
 				if(empty($unum_arr[1])==false){
					$temp_str=trim($unum_arr[1]);
					$temp_len=strlen($temp_str);
					$temp_arr=array();
					for($i=0;$i<$temp_len;$i++){
					
						$temp_arr[$i]=$temp_str[$i];
					
					}
					$new_str=implode(',',$temp_arr);
					$where.=" FIND_IN_SET (ten_num,'$new_str') OR";
				
				}
				if(empty($unum_arr[0])==false){
					$temp_str=trim($unum_arr[0]);
					$temp_len=strlen($temp_str);
					$temp_arr=array();
					for($i=0;$i<$temp_len;$i++){
					
						$temp_arr[$i]=$temp_str[$i];
					
					}
					$new_str=implode(',',$temp_arr);
					$where.=" FIND_IN_SET (hundred_num,'$new_str') OR";
				
				}
				$where=substr($where,0,-2);
				
				self::getMaxbonus($model,$where,$user_bonus,$lim_num);
				$model->where(" $where ")->setInc('cur_total_money',$user_bonus);	
		}



		//一码不定位

		function lim_ym_bdw($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				$temp_str=trim($unum);
				$temp_len=strlen($temp_str);
				$temp_arr=array();				
				for($i=0;$i<$temp_len;$i++){
				
					$temp_arr[$i]=$temp_str[$i];
				
				}
				$new_str=implode(',',$temp_arr);
				$where=" FIND_IN_SET (bit_num,'$new_str') OR FIND_IN_SET (ten_num,'$new_str') OR FIND_IN_SET (hundred_num,'$new_str')";
				self::getMaxbonus($model,$where,$user_bonus,$lim_num);
				$model->where(" FIND_IN_SET (bit_num,'$new_str') ")->setInc('cur_total_money',$user_bonus);

				$model->where("FIND_IN_SET (ten_num,'$new_str')")->setInc('cur_total_money',$user_bonus);
				
				$model->where("FIND_IN_SET (hundred_num,'$new_str')")->setInc('cur_total_money',$user_bonus);

				//$model->where("  FIND_IN_SET (ten_num,'$new_str') AND FIND_IN_SET (hundred_num,'$new_str') ")->setInc('cur_total_money',$user_bonus);
		
		}







		//二码不定位
		function lim_erm_bdw($uid,$unum,$lid,$buy_type,$mul,$yuan,$lim_num,$bonus_type){
		
				$model=self::getmodel($lid);
            $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
				$temp_str=trim($unum);
				$temp_len=strlen($temp_str);
				$temp_arr=array();				
				for($i=0;$i<$temp_len;$i++){
				
					$temp_arr[$i]=$temp_str[$i];
				
				}
				$new_str=implode(',',$temp_arr);
				$where=" (FIND_IN_SET (bit_num,'$new_str') AND  FIND_IN_SET (ten_num,'$new_str')) OR ( FIND_IN_SET (bit_num,'$new_str') AND FIND_IN_SET (hundred_num,'$new_str')) OR ( FIND_IN_SET (ten_num,'$new_str') AND FIND_IN_SET (hundred_num,'$new_str') )";
				self::getMaxbonus($model,$where,$user_bonus,$lim_num);
				$model->where(" $where ")->setInc('cur_total_money',$user_bonus);

		
		
		}



		function getMaxbonus($model,$where,$user_bonus,$lim_num){

				if($user_bonus>=$lim_num){

					echo json_encode(array("status"=>0,"info"=>"号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
					exit;					
				
				}
				$max_num=$model->where($where)->max('cur_total_money');
				if($max_num>=$lim_num){

					echo json_encode(array("status"=>0,"info"=>"该号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
					exit;				
				
				}elseif(($max_num+$user_bonus)>$lim_num){
				
					echo json_encode(array("status"=>0,"info"=>"该号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
					exit;				
				}

	
		
		}




		function getmodel($lid){
		
			switch($lid){
				case 11:
					$model=M('lim_3d');
					break;
				case 12:
					$model=M('lim_p3');
					break;		
			
			
			}

			return $model;

		
		
		}


	//获取用户的奖金与返点

	   function get_user_bonus_config($uid){

           import("Class.RedisObject");
           $redis = new \RedisObject();
           $redis->_setOption();

           $rows=$redis->_get("bonus_".$uid);

           if($rows){

               return $rows;

           }else{

               $rows=M("user_bonus")->where(array("userid"=>$uid))->find();

               $redis->_set("bonus_".$uid,json_decode($rows['bonus_content'],true),1800);

               return json_decode($rows['bonus_content'],true);
           }
	   }


		//获取用户的奖金计算

    public static function get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, $times){

        $_bonusTypeArr = explode("|",$bonus_type);
        $changeBonus = C("p3dchangeBonus");
        $p3d_bonus = C("psds_bonus");
        $p3d_bdw_bonus = C("psds_bonus_bdw");

        //获取用户奖金配置
        $user_bonus_config=self::get_user_bonus_config($uid);

        //获取彩票ID
        $temp_lid = 11;

        //定义用户奖金
        $user_bonus=0;
        //返点转奖金[设计的彩票,时时彩 11选5 快乐十分,福彩3d，排列三]
        if($_bonusTypeArr[2]==2 && in_array($lid,array(1,2,3,4,5,6,7,8,9,10,11,12,17,18,19,20))){
            if(strpos($buy_type,'不定位')!==false)
            {
                $fd = $_bonusTypeArr[1];
                $jj = $_bonusTypeArr[0];
                $_a_max = $user_bonus_config[$temp_lid]['bdw_ret_point'];
                $A = $_a_max-$fd;
                foreach ($changeBonus[$buy_type] as $k=>$v){
                    if(bccomp($k,$A,3)==0)
                        $kv =  $v;
                }

                if(bccomp($jj,$kv,3)!=0){
                    echo json_encode(array("status"=>0,"info"=>"返点奖金数据不正确"));
                    exit();
                } else {
                    $user_bonus=$jj*$mul*$times;
                }
            }else
            {
                $fd = $_bonusTypeArr[1];
                $jj = $_bonusTypeArr[0];
                $_a_max = $user_bonus_config[$temp_lid]['common_ret_point'];
                $A = $_a_max-$fd;

                foreach ($changeBonus[$buy_type] as $k=>$v){
                    if(bccomp($k,$A,3)==0)
                        $kv =  $v;
                }
                if(bccomp($jj,$kv,3)!=0){
                    echo json_encode(array("status"=>0,"info"=>"返点奖金数据不正确"));
                    exit();
                } else {
                    $user_bonus=$jj*$mul*$times;
                }
            }
        }else{
            if(strpos($buy_type,'不定位')!==false){
                $temp_bonus=$user_bonus_config[$temp_lid]['bdw_bonus'];
                $user_bonus=$p3d_bdw_bonus[$temp_bonus][$buy_type]*$mul*$times;
            }else{
                $temp_bonus=$user_bonus_config[$temp_lid]['common_bonus'];
                $user_bonus=$p3d_bonus[$temp_bonus][$buy_type]*$mul*$times;
            }
        }
        //角模式
        if($yuan==1){
            $user_bonus=$user_bonus/10;
        }elseif($yuan==2){
            $user_bonus=$user_bonus/100;
        }elseif($yuan==3){
            $user_bonus=$user_bonus/1000;
        }
        return $user_bonus;
    }

	
}

?>