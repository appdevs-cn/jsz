window.LOTTERYCONF = {
    "3d": {
        selectItem:[{}],
        orderItem:[],
        addItem:[],
        tabs: [{
            name: "三星",
            id: "3m"
        }, {
            name: "二星",
            id: "2m"
        }, {
            name: "定位胆",
            id: "dwd"
        }, {
            name: "不定位",
            id: "bdw"
        }, {
            name: "大小单双",
            id: "dxds"
        }],
        search: {
            "3m": [{
                name: "直选",
                games: [{
                    id: "3mzxfs",
                    alias:"直选复式",
                    name: "直选复式"
                }, {
                    id: "3mzxds",
                    alias:"直选单式",
                    name: "直选单式"
                }, {
                    id: "3mzxhz",
                    alias:"直选和值",
                    name: "直选和值"
                }]
            },{
                name: "组选",
                games: [{
                    id: "z3",
                    alias:"组三",
                    name: "组三"
                }, {
                    id: "z6",
                    alias:"组六",
                    name: "组六"
                }, {
                    id: "hhzx",
                    alias:"混合组选",
                    name: "混合组选"
                }]
            }],
            "2m": [{
                name: "前二",
                games: [{
                    id: "q2fs",
                    alias:"前二直选复式",
                    name: "直选复式"
                }, {
                    id: "q2ds",
                    alias:"前二直选单式",
                    name: "直选单式"
                }, {
                    id: "q2hz",
                    alias:"前二直选和值",
                    name: "直选和值"
                },{
                    id: "q2zxfs",
                    alias:"前二组选复式",
                    name: "组选复式"
                }, {
                    id: "q2zxds",
                    alias:"前二组选单式",
                    name: "组选单式"
                }, {
                    id: "q2zxhz",
                    alias:"前二组选和值",
                    name: "组选和值"
                }]
            },{
                name: "后二",
                games: [{
                    id: "h2fs",
                    alias:"后二直选复式",
                    name: "直选复式"
                }, {
                    id: "h2ds",
                    alias:"后二直选单式",
                    name: "直选单式"
                }, {
                    id: "h2hz",
                    alias:"后二直选和值",
                    name: "直选和值"
                },{
                    id: "h2zxfs",
                    alias:"后二组选复式",
                    name: "组选复式"
                }, {
                    id: "h2zxds",
                    alias:"后二组选单式",
                    name: "组选单式"
                }, {
                    id: "h2zxhz",
                    alias:"后二组选和值",
                    name: "组选和值"
                }]
            }],
            "dwd": [{
                name: "定位胆",
                games: [{
                    id: "dwd",
                    alias:"定位胆",
                    name: "定位胆"
                }]
            }],
            bdw: [{
                name: "三星不定位",
                games: [{
                    id: "1mbdw",
                    alias:"一码不定位",
                    name: "一码不定位"
                },{
                    id: "2mbdw",
                    alias:"二码不定位",
                    name: "二码不定位"
                }]
            }],
            "dxds": [{
                name: "大小单双",
                games: [{
                    id: "h2dxds",
                    alias:"后二大小单双",
                    name: "后二"
                },{
                    id: "q2dxds",
                    alias:"前二大小单双",
                    name: "前二"
                },{
                    id: "3mdxds",
                    alias:"三码大小单双",
                    name: "三码"
                }]
            }]
        },
        games: {
            "3mzxfs": {
                describe: {
                    title: "游戏玩法：从百位、十位、个位中选择一个3位数号码组成一注。",
                    content: "投注方案：123<br />从百位、十位、个位中选择一个3位数号码组成一注，所选号码与开奖号码相同，且顺序一致，即为中奖"
                },
                items: [{
                    title: "百",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]
                },{
                    title: "十",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]
                },{
                    title: "个",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]
                }]
            },
            "3mzxds": {
                describe: {
                    title: "游戏玩法：手动输入一个3位数号码组成一注,每注号码之间请用一个空格或英文逗号隔开。",
                    content: "投注方案：123<br />手动输入一个3位数号码组成一注，所选号码与开奖号码相同，且顺序一致，即为中奖。"
                },
                textarea: !0,
                fn: 'DirectFilter',
                limit: 3,
                filter: 'common'
            },
            "3mzxhz": {
                describe: {
                    title: "游戏玩法：所选数值等于开奖号码的三个数字相加之和，即为中奖。",
                    content: "投注方案：6<br />所选数值等于开奖号码的三个数字相加之和，即为中奖。"
                },
                items: [{
                    title: "直选和值",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27]
                }]
            },
            "z3": {
                describe: {
                    title: "从0-9中选择至少选择2个数字组成两注。",
                    content: "投注方案：12<br /> 从0-9中选择2个数字组成两注，所选号码与开奖号码的万位、千位、百位相同，且顺序不限，即为中奖"
                },
                items: [{
                    title: "组三",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "z6": {
                describe: {
                    title: "游戏玩法：从0-9中至少选择3个号码组成一注。",
                    content: "投注方案：123<br /> 从0-9中任意选择3个号码组成一注，所选号码与开奖号码的万位、千位、百位相同，顺序不限，即为中奖"
                },
                items: [{
                    title: "组六",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "hhzx": {
                describe: {
                    title: "游戏玩法：手动输入一个3位数号码组成一注(不含豹子号),每注号码之间请用一个空格或英文逗号隔开。",
                    content: "投注方案：123,345<br /> 手动输入一个3位数号码组成一注(不含豹子号)，开奖号码的万位、千位、百位符合后三组三或组六均为中奖"
                },
                textarea: !0,
                fn: "DirectFilter",
                limit:3,
                filter:'hhFilter'
            },
            "q2fs": {
                describe: {
                    title: "游戏玩法：从百位、十位中选择一个2位数号码组成一注。",
                    content: "投注方案：12<br /> 从百位、十位中选择一个2位数号码组成一注，所选号码与开奖号码的前2位相同，且顺序一致，即为中奖"
                },
                items: [{
                    title: "百",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }, {
                    title: "十",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "q2ds": {
                describe: {
                    title: "游戏玩法：手动输入一个2位数号码组成一注,每注号码之间请用一个空格或英文逗号隔开。",
                    content: "投注方案：12,34<br /> 手动输入一个2位数号码组成一注，所选号码的百位、十位与开奖号码相同，且顺序一致，即为中奖"
                },
                textarea: !0,
                fn: "DirectFilter",
                limit:2,
                filter:'common'
            },
            "q2hz": {
                describe: {
                    title: "游戏玩法：从0至18里选择一个数值进行投注。",
                    content: "投注方案：10<br /> 所选数值等于开奖号码的百位、十位二个数字相加之和，即为中奖"
                },
                items: [{
                    title: "和值",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]
                }]
            },
            "q2zxfs": {
                describe: {
                    title: "游戏玩法：从0-9中至少选2个号码组成一注。",
                    content: "投注方案：12<br /> 从0-9中选2个号码组成一注，所选号码与开奖号码的百位、十位相同，顺序不限（不含对子号），即中奖"
                },
                items: [{
                    title: "前二组选",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "q2zxds": {
                describe: {
                    title: "游戏玩法：手动输入一个2位数号码组成一注,每注号码之间请用一个空格或英文逗号隔开。",
                    content: "投注方案：12,34<br /> 手动输入一个2位数号码组成一注，所选号码的百位、十位与开奖号码相同，顺序不限（不含对子号），即为中奖"
                },
                textarea: !0,
                fn:"DirectFilter",
                limit:2,
                filter:'2xzxFilter'
            },
            "q2zxhz": {
                describe: {
                    title: "游戏玩法：从1至17里选择一个数值进行投注。",
                    content: "投注方案：10<br /> 所选数值等于开奖号码的百位、十位二个数字相加之和（不含对子号），即为中奖"
                },
                items: [{
                    title: "和值",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
                }]
            },
            "h2fs": {
                describe: {
                    title: "游戏玩法：从十位、个位中选择一个2位数号码组成一注。",
                    content: "投注方案：12<br /> 从百位、十位中选择一个2位数号码组成一注，所选号码与开奖号码的前2位相同，且顺序一致，即为中奖"
                },
                items: [{
                    title: "十",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }, {
                    title: "个",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "h2ds": {
                describe: {
                    title: "游戏玩法：手动输入一个2位数号码组成一注,每注号码之间请用一个空格或英文逗号隔开。",
                    content: "投注方案：12,34<br /> 手动输入一个2位数号码组成一注，所选号码的十位、个位与开奖号码相同，且顺序一致，即为中奖"
                },
                textarea: !0,
                fn: "DirectFilter",
                limit:2,
                filter:'common'
            },
            "h2hz": {
                describe: {
                    title: "游戏玩法：从0至18里选择一个数值进行投注。",
                    content: "投注方案：10<br /> 所选数值等于开奖号码的十位、个位二个数字相加之和，即为中奖"
                },
                items: [{
                    title: "和值",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]
                }]
            },
            "h2zxfs": {
                describe: {
                    title: "游戏玩法：从0-9中至少选2个号码组成一注。",
                    content: "投注方案：12<br /> 从0-9中选2个号码组成一注，所选号码与开奖号码的十位、个位相同，顺序不限（不含对子号），即中奖"
                },
                items: [{
                    title: "后二组选",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "h2zxds": {
                describe: {
                    title: "游戏玩法：手动输入一个2位数号码组成一注,每注号码之间请用一个空格或英文逗号隔开。",
                    content: "投注方案：12,34<br /> 手动输入一个2位数号码组成一注，所选号码的十位、个位与开奖号码相同，顺序不限（不含对子号），即为中奖"
                },
                textarea: !0,
                fn:"DirectFilter",
                limit:2,
                filter:'2xzxFilter'
            },
            "h2zxhz": {
                describe: {
                    title: "游戏玩法：从1至17里选择一个数值进行投注。",
                    content: "投注方案：10<br /> 所选数值等于开奖号码的十位、个位二个数字相加之和（不含对子号），即为中奖"
                },
                items: [{
                    title: "和值",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
                }]
            },
            dwd: {
                describe: {
                    title: "游戏玩法：从百位、十位、个位任意位置上至少选择1个以上号码。",
                    content: "投注方案：1<br /> 从百位、十位、个位任意位置上至少选择1个以上号码，所选号码与相同位置上的开奖号码一致，即为中奖"
                },
                items: [{
                    title: "百",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }, {
                    title: "十",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }, {
                    title: "个",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "1mbdw": {
                describe: {
                    title: "游戏玩法：从0-9中选择1个号码，每注由1个号码组成。",
                    content: "投注方案：1<br /> 从0-9中选择1个号码，每注由1个号码组成，只要开奖号码的百位、十位、个位中包含所选号码，即为中奖"
                },
                items: [{
                    title: "一码不定位",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            "2mbdw": {
                describe: {
                    title: "游戏玩法：从0-9中选择2个号码，每注由2个不同的号码组成。",
                    content: "投注方案：12<br /> 从0-9中选择2个号码，每注由2个不同的号码组成，开奖号码的百位、十位、个位中同时包含所选的2个号码，即为中奖"
                },
                items: [{
                    title: "二码不定位",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }]
            },
            h2dxds: {
                describe: {
                    title: "游戏玩法：对十位、个位的“大（56789）小（01234）、单（13579）双（02468）”形态进行购买。",
                    content: "投注方案：小大<br /> 对十位、个位的“大（56789）小（01234）、单（13579）双（02468）”形态进行购买，所选号码的位置、形态与开奖号码的位置、形态相同，即为中奖"
                },
                items: [{
                    title: "十位",
                    nums: ["大","小","单","双"]
                }, {
                    title: "个位",
                    nums: ["大","小","单","双"]
                }]
            },
            q2dxds: {
                describe: {
                    title: "游戏玩法：对百位、十位的“大（56789）小（01234）、单（13579）双（02468）”形态进行购买。",
                    content: "投注方案：小大<br /> 对百位、十位的“大（56789）小（01234）、单（13579）双（02468）”形态进行购买，所选号码的位置、形态与开奖号码的位置、形态相同，即为中奖"
                },
                items: [{
                    title: "百位",
                    nums: ["大","小","单","双"]
                }, {
                    title: "十位",
                    nums: ["大","小","单","双"]
                }]
            },
            "3mdxds": {
                describe: {
                    title: "游戏玩法：对百位、十位、个位的“大（56789）小（01234）、单（13579）双（02468）”形态进行购买。",
                    content: "投注方案：大小大<br /> 对百位、十位、个位的“大（56789）小（01234）、单（13579）双（02468）”形态进行购买，所选号码的位置、形态与开奖号码的位置、形态相同，即为中奖"
                },
                items: [{
                    title: "百位",
                    nums: ["大","小","单","双"]
                }, {
                    title: "十位",
                    nums: ["大","小","单","双"]
                }, {
                    title: "个位",
                    nums: ["大","小","单","双"]
                }]
            }
        }
    }
}

var e = null,
    display = $('div.lm0'),  // 显示面板容器
    games = $("#barea_tab"), // 玩法统称
    gamec = $("#game_search"),// 玩法统称下的具体玩法
    gamen = $("#game_betting"),// 号码选择区
    howtoplay = $("#howtoplay"),
    $lid = $('input[name=lid]').val(),
    prize = $("select[name=prize]"),
    cartlist = $('#cartlist'),
    r = LOTTERYCONF[$('#lottery_id').val()];// 获取玩法的配置参数
!(function(win){
    var ssc = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,lossLine: function()
        {
            $(".J__filter-lossLine").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".selectLists").stop(true).slideToggle(300);
            });
        }
        ,TrHover: function()
        {
            $("#J__trHover tr").hover(function(){
                $(this).addClass("on");
            }, function(){
                $(this).removeClass("on");
            });
        }
        ,LotteryRecord: function()
        {
            $(".J__lotteryRecord").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".lotteryRecord-list").stop(true).slideToggle(300);
            });
        }
        ,PopHelp: function()
        {
            $(".J__popHelp").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".cont").stop(true).slideToggle(300);
            });
        }
        ,SelUnit: function()
        {
            $("#J__selUnit i").on("click", function(){
                $(this).addClass("on").siblings().removeClass("on");
                mode = $(this).attr("data-field");
                $singermoneyval = $("#singermoney")
                $singernoteval = parseInt($("#singernote").text())
                $multipleval = parseInt($('input[name=multiple]').val())
                switch (parseInt(mode))
                {
                    case 0:
                        $modelCurrentMoney = $singernoteval*$multipleval*2
                        break;
                    case 1:
                        $modelCurrentMoney = $singernoteval*$multipleval*2/10
                        break;
                    case 2:
                        $modelCurrentMoney = $singernoteval*$multipleval*2/100
                        break;
                    case 3:
                        $modelCurrentMoney = $singernoteval*$multipleval*2/1000
                        break;
                }
                $singermoneyval.text($modelCurrentMoney)
                r.selectItem[0].model = mode
                r.selectItem[0].singermoney = $modelCurrentMoney
            });
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.JszContent',function(){
                ssc.LoadingAfter()
                /*todo 初始点击事件*/
                games.on("click", "a", function(c) {
                    var n = $(c.target);
                    n.hasClass("on") || (e = n.attr("id"), $(".on", games).removeClass("on"), n.addClass("on"), ssc.gamea({
                        search: r.search[e],
                        on: r.search[e][0].games[0].id
                    }), ssc.gamet({
                        games: r.games[r.search[e][0].games[0].id]
                    }), ssc.gamem({
                        bonus: r.bonus,
                        bonuslevel: r.selectItem[0].bonusLevel
                    },r.search[e][0]['games'][0]['alias']),ssc.initSetDefaultOption(e))
                }), $("#game_search").on("click", "a", function(s) {
                    ssc.gamea({
                        search: r.search[e],
                        on: s.target.id
                    }), ssc.gamet({
                        games: r.games[s.target.id]
                    }), ssc.gamem({
                        bonus: r.bonus,
                        bonuslevel: r.selectItem[0].bonusLevel
                    },$(s.target).attr('data-field')), ssc.t_oninput($(s.target).attr('data-field')),ssc.emptyText(),ssc.filterData(),ssc.importFile(),ssc.init({
                        "playname":$(s.target).attr('data-field')
                        ,"showplayname":$(s.target).text()
                    })
                }),games.html(tpl("#tabs_tpl", r)).find("a:eq(0)").trigger("click")

                $('#game_betting').on('click', '.lottery-ball', function(event) {
                    var $target = $(event.target)
                    $target[$target.hasClass('on') ? 'removeClass' : 'addClass']('on')
                    ssc.calculateMultipleNote()
                    event.stopPropagation()
                }).on('click', '.lottery-ball-other', function(e) {
                    var $target = $(e.target)
                    var $balls = $target.closest('li').find('.lottery-ball')
                    var max = Math.round($balls.length / 2)
                    switch ($target.attr('data-fn')) {
                        case '1':
                            $balls.addClass('on')
                            break
                        case '2':
                            $.each($balls, function(i, d) {
                                $(d)[i > max - 1 ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '3':
                            $.each($balls, function(i, d) {
                                $(d)[i < max ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '4':
                            $.each($balls, function(i, d) {
                                $(d)[parseInt($(d).text()) % 2 > 0 ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '5':
                            $.each($balls, function(i, d) {
                                $(d)[parseInt($(d).text()) % 2 < 1 ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '6':
                            $balls.removeClass('on')
                            break
                    }
                    ssc.calculateMultipleNote()
                })

                /*todo 直选和值*/
                window.zxhz = function()
                {
                    $config = {0:1,1:3,2:6,3:10,4:15,5:21,6:28,7:36,8:45,9:55,10:63,11:69,12:73,13:75,14:75,15:73,16:69,17:63,18:55,19:45,20:36,21:28,22:21,23:15,24:10,25:6,26:3,27:1}
                    $row1 = new Array()
                    $sum = 0
                    $("#J__selNums li:eq(0)").find(".lottery-ball").each(function(){
                        if($(this).hasClass("on"))
                            $row1.push($(this).attr("data-val"))
                    })
                    for (var i=0; i<$row1.length; i++) {
                        $sum += $config[$row1[i]];
                    }
                    return $sum
                }

                /*todo 组三*/
                window.z3Func = function()
                {
                    $select = window.select
                    $count = $select[0]
                    if($count<2) return 0
                    return $count*($count-1)
                }

                /*todo 组六*/
                window.z6Func = function()
                {
                    $select = window.select
                    $count = $select[0]
                    z6c = 1, z6n = 1, z6m = 1
                    if($count<3) return 0
                    for (var i=$count; i>0; i--) {
                        z6c *= i;
                    }
                    for (var k=3; k>0; k--) {
                        z6m *= k;
                    }
                    for (var j=($count-3); j>0; j--) {
                        z6n *= j;
                    }
                    return z6c/(z6n*z6m)
                }

                /*todo 2星直选和值*/
                window.zxhzFunc = function()
                {
                    $select = window.select
                    $count = $select[0]
                    $row1 = new Array()
                    $sum = 0
                    $config = {0:1,1:2,2:3,3:4,4:5,5:6,6:7,7:8,8:9,9:10,10:9,11:8,12:7,13:6,14:5,15:4,16:3,17:2,18:1}
                    $("#J__selNums li:eq(0)").find(".lottery-ball").each(function(){
                        if($(this).hasClass("on"))
                            $row1.push($(this).attr("data-val"))
                    })
                    for (var i=0; i<$row1.length; i++) {
                        $sum += $config[$row1[i]];
                    }
                    return $sum
                }

                /*todo 2星组选复式*/
                window.zxfsFunc = function()
                {
                    $select = window.select
                    $count = $select[0]
                    z6c = 1, z6n = 1, z6m = 1
                    if($count<2) return 0
                    for (var i=$count; i>0; i--) {
                        z6c *= i;
                    }
                    for (var k=2; k>0; k--) {
                        z6m *= k;
                    }
                    for (var j=($count-2); j>0; j--) {
                        z6n *= j;
                    }
                    return z6c/(z6n*z6m)
                }

                /*todo 2星组选和值*/
                window.zxhFunc = function()
                {
                    $select = window.select
                    $count = $select[0]
                    $row1 = new Array()
                    $sum = 0
                    $config = {1:1,2:1,3:2,4:2,5:3,6:3,7:4,8:4,9:5,10:4,11:4,12:3,13:3,14:2,15:2,16:1,17:1}
                    $("#J__selNums li:eq(0)").find(".lottery-ball").each(function(){
                        if($(this).hasClass("on"))
                            $row1.push($(this).attr("data-val"))
                    })
                    for (var i=0; i<$row1.length; i++) {
                        $sum += $config[$row1[i]];
                    }
                    return $sum
                }

                /*todo 定位胆*/
                window.dwdFunc = function()
                {
                    $select = window.select
                    $sum = 0
                    for(var i=0; i<$select.length; i++)
                    {
                        $sum += parseInt($select[i])
                    }
                    return $sum
                }

                /*todo 2码不定位*/
                window.m2Func = function()
                {
                    $select = window.select
                    $count = $select[0]
                    z6c = 1, z6n = 1, z6m = 1
                    if($count<2) return 0
                    for (var i=$count; i>0; i--) {
                        z6c *= i;
                    }
                    for (var k=2; k>0; k--) {
                        z6m *= k;
                    }
                    for (var j=($count-2); j>0; j--) {
                        z6n *= j;
                    }
                    return z6c/(z6n*z6m)
                }
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            ssc.RefreshMoney(),ssc.Logout(),ssc.displayPanl(),ssc.socketEvent(),ssc.SelUnit()
            ssc.xySlider(),ssc.Dropdown(),ssc.NavMenu(),ssc.NoticeSlider(),ssc.lossLine(),ssc.FloatToolbar(),ssc.FloatAddToolbar()
        }
        ,FloatToolbar: function()
        {
            $(".J__floatToolbar").hover(function(){
                $(this).find(".popup__floatBox").stop(true).fadeIn(200);
            }, function(){
                $(this).find(".popup__floatBox").stop(true).hide();
            });
        }
        ,FloatAddToolbar: function()
        {
            $(".J__floatAddToolbar").hover(function(){
                $(this).find(".popup__floatBox_add").stop(true).fadeIn(200);
            }, function(){
                $(this).find(".popup__floatBox_add").stop(true).hide();
            });
        }
        ,RefreshMoney: function()
        {
            // 金额刷新
            $(".getnewmoney").bind("click",function(){
                $.post("/getMoney",function(money){
                    var arr = money.split('-')
                    $(".usermoney").html("可用余额 ￥"+arr[0]);
                    $(".wallet_account").html("分红钱包 ￥"+arr[1]);
                },"text")
            })
        }
        ,getMoney: function()
        {
            $.post("/getMoney",function(money){
                var arr = money.split('-')
                $(".usermoney").html("可用余额 ￥"+arr[0]);
                $(".wallet_account").html("分红钱包 ￥"+arr[1]);
            },"text")
        }
        ,Logout: function()
        {
            // 退出系统
            $.dialog.close('*');
            $(document).on('click', 'a[data-method=out]', function(e) {
                $uid = $('input[name=uid]').val()
                layui.use('layer', function(layer){
                    var index = layer.confirm('确定要退出系统吗？', {
                        btn: ['退出','玩一会'] 
                        ,anim:3
                        ,btnAlign: 'c'
                        }, function(){
                            window.location.href = '/logout';
                            $('input[name=uid]').val(" ");
                            $('input[name=path]').val(" ");
                            if ($uid != "")
                                window.socket.emit("logout", $uid);
                            layer.close(index)
                        });
                })
            })
        }
        ,xySlider: function()
        {
            // 大图轮播
            var $slider = $(".js_idxSlider"), $nav = $(".slider-nav i");
            $slider.jq_xySlider({
                effect: "fade",
                autoplay:true,
                delay: 5000,
                onEnd: function(idx){
                    $nav.removeClass("on").eq(idx).addClass("on");
                },
                navigation: $nav
            });
        }
        ,Dropdown: function()
        {
            $(".J__dropdown").hover(function(){
				$(this).addClass("active").siblings().removeClass("on");
				$(this).find(".dropdownMenu2").stop(true).slideDown(200);
			}, function(){
				$(this).removeClass("active");
				$(".J__navMenu .current").addClass("on");
				$(this).find(".dropdownMenu2").stop(true).hide();
			});
        }
        ,NavMenu: function()
        {
            $(".J__navMenu li:not('.u-info')").hover(function(){
				$(this).addClass("on").siblings().removeClass("on");
			}, function(){
				$(this).removeClass("on");
				$(".J__navMenu .current").addClass("on");
			});
        }
        ,PlaySoundHandler: function()
        {
            ssc.PlaySound('/Resourse/Home/Sound/kai.wav')
            document.getElementById("play1").addEventListener("ended", function(){document.getElementById("play2").play()});
            document.getElementById("play2").addEventListener("ended", function(){document.getElementById("play3").play()}); 
            document.getElementById("play3").addEventListener("ended", function(){document.getElementById("play4").play()});
        }
        ,PlaySound: function(src)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV').html('<embed id="play1" src="'+src+'"/>'); 
            }
            else
            {
                $('#newMessageDIV').html('<audio id="play1" autoplay="autoplay"><source src="'+src+'"'+ 'type="audio/wav"/></audio>');
            }
        }
        ,PlaySound1: function(n)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV0').html('<embed id="play2" src="/Resourse/Home/Sound/0.wav"/>'); 
            }
            else
            {
                $('#newMessageDIV0').html('<audio id="play2"><source src="/Resourse/Home/Sound/'+n+'.wav" type="audio/wav"/></audio>');
            }
        }
        ,PlaySound2: function(n)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV1').html('<embed id="play3" src="/Resourse/Home/Sound/0.wav"/>'); 
            }
            else
            {
                $('#newMessageDIV1').html('<audio id="play3"><source src="/Resourse/Home/Sound/'+n+'.wav" type="audio/wav"/></audio>');
            }
        }
        ,PlaySound3: function(n)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV2').html('<embed id="play4" src="/Resourse/Home/Sound/0.wav"/>'); 
            }
            else
            {
                $('#newMessageDIV2').html('<audio id="play4"><source src="/Resourse/Home/Sound/'+n+'.wav" type="audio/wav"/></audio>');
            }
        }
        ,displayPanl: function()
        {
            $.post("/DisplayBuy/timeDown",{"lid":$lid},function(data){
                display.html(tpl("#display_tpl",{rows:[data]})),ssc.runIt(data['last_number'])
                /* todo 显示最近5期开奖 */
                ssc.LotteryRecord()
                r.bonus = [data['bonus']]
                r.currentid = data['id']
                r.selectItem[0].lid = data['lid']
                r.selectItem[0].currentid = data['id']
                r.selectItem[0].bonusLevel = data['bonuslevel']
                r.selectItem[0].bdw_ret_point = data['bdw_ret_point']
                var endtime = data['endtime'];
                $("#current_endtime").html(endtime);
                ssc.setSelectItemHeighPrize()
                ssc.getBuyRecordItem()
                ssc.gamem({bonus: r.bonus,bonuslevel: r.selectItem[0].bonusLevel},r.selectItem[0].playname)

                var stoptimeArr = data['endtime'].split(" ")
                var stopTime1 = stoptimeArr[0].split("-")
                var stopTime2 = stoptimeArr[1].split(":")
                var stop = new Date(stopTime1[0],stopTime1[1],stopTime1[2],stopTime2[0],stopTime2[1],stopTime2[2])

                var serverTimeArr = data['servertime'].split(" ")
                var serverTime1 = serverTimeArr[0].split("-")
                var serverTime2 = serverTimeArr[1].split(":")
                var ServerDate = new Date(serverTime1[0],serverTime1[1],serverTime1[2],serverTime2[0],serverTime2[1],serverTime2[2])
                var now = new Date()
                var ClientDate= new Date(now.getFullYear(),now.getMonth()+1,now.getDate(),now.getHours(),now.getMinutes(),now.getSeconds())
                // 本地时间与服务器的时间差
                var d = ClientDate.getTime()-ServerDate.getTime()
                $("input[name='d']").val(d);
                clock();
            },'json')
            
            function clock(){
                var today = new Date();
                var d = $("input[name='d']").val();
                today.setTime(today.getTime()-d);
                var nowtoday = new Date(today.getFullYear(),today.getMonth()+1,today.getDate(),today.getHours(),today.getMinutes(),today.getSeconds());
                var stoptime = $("#current_endtime").html();
                var stoptimeArr = stoptime.split(" ");
                var stopTime1 = stoptimeArr[0].split("-");
                var stopTime2 = stoptimeArr[1].split(":");
                var stop = new Date(stopTime1[0],stopTime1[1],stopTime1[2],stopTime2[0],stopTime2[1],stopTime2[2]);
                var leave=stop-nowtoday;
                if(leave>=1000){
                    var timestamp = leave/1000;
                    var second = Math.floor( timestamp % 60);
                    var minute = Math.floor((timestamp / 60)	% 60);
                    var hour   = Math.floor((timestamp / 3600)	% 24);
                    var day    = Math.floor((timestamp / 3600)	/ 24);
                    var h = ('0'+hour).slice(-2);
                    var m = ("0"+minute).slice(-2);
                    var s = ("0"+second).slice(-2);
                    $("#count_down").html('<i>'+ h+'</i><i>'+ m+'</i><i>'+ s+'</i>');
                    setTimeout(clock, 1000);
                } else {
                    if($("li",cartlist).length>1)
                    {
                        $.dialog.close('*')
                        layui.use('layer', function(layer){
                            var index = layer.confirm('购物车还有未提交的数据,是否清除？', {
                                btn: ['确定清除','保留购买'] 
                                ,anim:3
                                ,btnAlign: 'c'
                                }, function(){
                                    ssc.cleanCartlist()
                                    layer.close(index)
                                });
                        })
                    }
                    else
                    {
                        $.dialog.close('*');
                        alertify.success("<div class='text'><i class='ico-success'></i>该期已结束，进入下期购买，请留意期号变化<i class='suc-close'></i></div>")
                    }
                    ssc.displayPanl();
                }
            }
        }
        ,runIt: function(num)
        {
            for(var i=0; i<num.length; i++)
            {
                $("#num"+i).text(num[i])
            }
        }
        ,socketEvent: function()
        {
            $event="";
            switch (parseInt($lid))
            {
                case 11:
                    $event = "3dOpenCode";break;
                case 12:
                    $event = "p3OpenCode";break;
            }
            window.socket.on($event,function(data)
            {
                $json = JSON.parse(data);
                $("#lastSeries").text($json.series_number);
                ssc.runIt($json.number)
                var _recentHtml = '<tr><td>'+$json.series_number+'</td><td><div class="number">'
                for(var j=0; j<$json.number.length; j++)
                {
                    _recentHtml += '<i>'+$json.number[j]+'</i>';
                }
                _recentHtml += '</div></td></tr>';
                $("#game_jwuqi>tbody").find("tr:last").remove()
                $("#game_jwuqi>tbody").find("tr:first").before(_recentHtml);
                ssc.getBuyRecordItem()
            })
            window.socket.on("message", function($data) {
                var $json = $data.split('-')
                if ($json[1] == 1) {
                    ssc.getBuyRecordItem()
                }
            })
        }
        ,getBuyRecordItem: function()
        {
            $.post("/buyRecordItem",{"lid":$lid},function(data){
                $("#buyrecorditem").html(tpl("#buyrecord_tpl",{rows:[data]}))
                $("a[data-method=detail]").on("click",function(){
                    $buyid = $(this).attr("data-field");
                    $.dialog.open({
                        title: '订单详情',
                        width: 800,
                        height:590,
                        btnText:["关 闭"],
                        type: 'alert'
                    }).ready(function(o) {
                        $.post('/selfRecordDetail',{"id":$buyid},function(json){
                            if(json.length==0) return false;
                            o.html(tpl('#detail_record',{rows:[json]}))
                        },'json')
                    })
                })
                $("a[data-method=cancel]").on("click",function(e){
                    var $item = $(e.target).closest('tr')
                    $buyid = $(this).attr("data-field");
                    $.post("/recordCancel",{"id":$buyid},function(msg){
                        if(msg==true)
                        {
                            $item.find("td:eq(4)").html('<span style="color:red">已撤单</span>');
                            $(e.target).remove();
                            alertify.success("<div class='text'><i class='ico-success'></i>订单取消成功<i class='suc-close'></i></div>")
                            ssc.getMoney()
                        }
                        else
                        {
                            $.dialog.close("*")
                            alertify.error("<div class='text'><i class='ico-error'></i>订单取消成功<i class='err-close'></i></div>")
                        }
                        return;
                    },'text')
                })
            },'json')
        }
        ,gamea: function(a)
        {
            gamec.html(tpl("#search_tpl", a))
        }
        ,gamet: function(a)
        {
            gamen.html(tpl("#bet_tpl", a))
            howtoplay.html(tpl("#howtoplay_tpl", a))
            ssc.PopHelp()
        }
        ,gamem: function(a,n)
        {
            $n = String(n);
            if(typeof(a['bonus'])=="undefined") return;
            if($n=="混合组选")
            {
                var _html = '<option value="1">奖金'+ a['bonus'][0]['common']['组三']+'/'+a['bonus'][0]['common']['组六']+'-'+a['bonuslevel']+'%</option>';
                _html += '<option selected value="2">奖金'+ a['bonus'][0]['change']['组三']+'/'+a['bonus'][0]['change']['组六']+'-0%</option>';
            }
            else
            {
                var _html = '<option value="1">奖金'+ a['bonus'][0]['common'][$n]+'-'+a['bonuslevel']+'%</option>';
                _html += '<option selected value="2">奖金'+ a['bonus'][0]['change'][$n]+'-0%</option>';
            }
            prize.html(_html);
        }
        ,t_oninput: function(p)
        {
            if(String(p).indexOf("单式")>0 || String(p).indexOf("混合组选")>0)
            {
                var element = document.getElementById("lt_write_box");
                if("\v"=="v")
                {
                    element.onpropertychange = function()
                    {
                        var s = element.value;
                        s = s.replace(/[^\d;\s,]/g," ");
                        element.value = s;
                        s = s.replace(/[,;\s]/g," ")
                        s = s.replace(/(^\s*)|(\s*$)/g,"")
                        var a = (s!="") ? s.split(" ") : "";
                        $result = ssc.checkUnitaryMate(a);
                        if($result['result']!="")
                            a = $result['result'].split(" ");
                        else
                            a = []

                        ssc.calculateCommonUnitary(a.length)
                        ssc.setSingerSelectItemNumber($result['result'])
                    }
                }
                else
                {
                    element.addEventListener("input",function(){
                        var s = element.value;
                        s = s.replace(/[^\d;\s,]/g," ");
                        element.value = s;
                        s = s.replace(/[,;\s]/g," ")
                        s = s.replace(/(^\s*)|(\s*$)/g,"")
                        var a = (s!="") ? s.split(" ") : "";
                        $result = ssc.checkUnitaryMate(a);
                        if($result['result']!="")
                            a = $result['result'].split(" ");
                        else
                            a = []
                        ssc.calculateCommonUnitary(a.length)
                        ssc.setSingerSelectItemNumber($result['result'])
                        
                    },false);
                }
            }
        }
        ,checkUnitaryMate: function(a)
        {
            $fn = $("#lt_write_box").attr("data-method")
            $filter = $("#lt_write_box").attr("data-fn")
            $limit = $("#lt_write_box").attr('data-field')
            $result = SetFilterFunc($fn,a,$limit,$filter);
            return $result;
        }
        ,emptyText: function()
        {
            $("a[data-method=empty-text]").on("click",function(){
                var element = document.getElementById("lt_write_box");
                element.value = "";
                $n = r.selectItem[0]['playname']
                a = ""
                ssc.calculateCommonUnitary(a.length)
                ssc.setSingerSelectItemNumber("")
            })
        }
        ,filterData: function()
        {
            $("a[data-method=filter-data]").on("click",function(){
                var element = document.getElementById("lt_write_box");
                var s = element.value;
                s = s.replace(/[,;\s]/g," ")
                s = s.replace(/(^\s*)|(\s*$)/g,"")
                var a = (s!="") ? s.split(" ") : "";
                if(a!="")
                {
                    res = window.filterArray(a);
                    result = res['result'];
                    filter = res['filter'];

                    if(result=="") return false;
                    element.value = result.join(" ")
                    s = element.value;
                    var a = (s!="") ? s.split(" ") : "";
                    p = $('.selected',"#game_search").attr("data-field");
                    ssc.calculateCommonUnitary(a.length)
                    ssc.setSingerSelectItemNumber(result.join(" "))
                    if(filter.length>0)
                    {
                        $data = filter.join(" ")
                        $.dialog.open({
                            title: '过滤的数据',
                            width: 600,
                            btnText:['关闭'],
                            type: 'alert'
                        }).ready(function(o) {
                            o.html(tpl('#filternumber_tpl',{rows:$data}))
                        }).confirm(function() {
                            $.dialog.close("*")
                            return false
                        })
                    }
                }
                else
                {
                    return false;
                }
            })
        }
        ,importFile: function()
        {
            $('a[data-method=import-file]').on('click',function(){
                layui.use('layer', function(layer){
                    var index = layer.confirm('<div style="text-align:center"><a href="javascript:;" class="a-upload"><input type="file" name="" id="file"><s class="fa fa-file"></s>上传文件txt文件</a></div>', {
                        title:'导入号码'
                        ,btn: ['导入号码','关闭'] 
                        ,anim:3
                        ,btnAlign: 'c'
                        }, function(){
                            if(typeof FileReader == 'undefined') {
                                return false;
                            }
                            var simpleFile = document.getElementById("file").files[0];
                            var reader = new FileReader();
                            // 将文件以文本形式读入页面中
                            reader.readAsText(simpleFile);
                            reader.onload = function(e){
                                var element = document.getElementById("lt_write_box");
                                result = this.result.replace(/[^\d]/g," ").replace(/(^\s*)|(\s*$)/g,"")
                                element.value = result
                                s = result;
                                var a = (s!="") ? s.split(" ") : "";
                                $result = ssc.checkUnitaryMate(a);
                                if($result['result']!="")
                                    a = $result['result'].split(" ");
                                else
                                    a = []
                                p = $('.selected',"#game_search").attr("data-field");
                                ssc.calculateCommonUnitary(a.length)
                                ssc.setSingerSelectItemNumber($result['result'])
                            }
                            layer.close(index)
                        });
                })
            })
        }
        ,cleanCartlist: function()
        {
            $("li",cartlist).remove()
            ssc.calculateCartlist();
        }
        ,addCartlist: function()
        {
            if(ssc.isInsite())
            {
                order = ssc.setOrderTempData()
                id = r.orderItem.length
                selnum = r.selectItem[0].selectNumber
                switch(parseInt(r.selectItem[0].model))
                {
                    case 0:
                        $model = '元模式';break;
                    case 1:
                        $model = '角模式';break;
                    case 2:
                        $model = '分模式';break;
                    case 3:
                        $model = '厘模式';break;
                }

                showselnum = (selnum.length>10) ? (selnum.substr(0,50)+'...') : selnum
                var _html = '<li>'
                _html += '<em>'+r.selectItem[0].play+'_'+r.selectItem[0].showplayname+'</em>'
                _html += '<em>'+r.selectItem[0].multiple+'倍</em>'
                _html += '<em>'+$model+'</em>'
                _html += '<em>'+showselnum+'</em>'
                _html += '<em>'+r.selectItem[0].singermoney+'</em>'
                _html += '<em><a href="javascript:;" data-field="'+id+'" title="删除订单" data-method="delete"><img src="/Resourse/Home/images/cp/icon_del-num.png" /></a></em></li>'
                $("#cartlist").append(_html)
                r.orderItem.push(order)
                ssc.cleanSelectItemData()
                ssc.calculateCartlist()
            }
            else
            {
                return false;
            }
        }
        ,isInsite: function()
        {
            if(r.selectItem[0].selectNumber=="" || r.selectItem[0].singermoney==0 || r.selectItem[0].singernote==0)
            {
                return false
            }
            else
            {
                return true
            }
        }
        ,setOrderTempData: function()
        {
            order = new Object()
            order.bonusLevel = r.selectItem[0].bonusLevel
            order.currentid = r.selectItem[0].currentid
            order.lid = r.selectItem[0].lid
            order.model = r.selectItem[0].model
            order.multiple = r.selectItem[0].multiple
            order.playname = r.selectItem[0].playname
            order.position = r.selectItem[0].position
            order.prize = r.selectItem[0].prize
            order.selectNumber = r.selectItem[0].selectNumber
            order.singermoney = r.selectItem[0].singermoney
            order.singernote = r.selectItem[0].singernote
            return order
        }
        ,cleanSelectItemData: function()
        {
            r.selectItem[0].selectNumber=""
            r.selectItem[0].singermoney=0
            r.selectItem[0].singernote=0
        }
        ,calculateCartlist: function()
        {
            var orderCount = 0;
            var orderMoney = 0;
            $("li",cartlist).each(function(i){
                orderCount = i+1;
                $m = $("em:eq(4)",this).text();
                orderMoney = orderMoney + parseFloat($m);
            })
            $("#orderCount").text(orderCount);
            $("#orderMoney").text(orderMoney);
            ssc.addCartlistAfterHandler()
        }
        ,addCartlistAfterHandler: function()
        {
            $n = r.selectItem[0]['playname']
            if(String($n).indexOf("单式")>0  || String($n).indexOf("混合组选")>0)
            {
                var element = document.getElementById("lt_write_box");
                element.value = "";
            }
            $("#J__selNums li").find(".lottery-ball").removeClass("on")
            $("#singernote").text(0)
            $("#singermoney").text(0)
        }
        ,calculateCommonUnitary: function(note)
        {
            var multiple = $("input[name=multiple]").val();
            var m;
            $("#J__selUnit").find('i').each(function(){
                if($(this).hasClass('on'))
                    m = $(this).attr('data-field')
            })
            switch (parseInt(m))
            {
                case 0:
                    $currentmoney = note*multiple*2
                    break
                case 1:
                    $currentmoney = note*multiple*2/10
                    break
                case 2:
                    $currentmoney = note*multiple*2/100
                    break
                case 3:
                    $currentmoney = note*multiple*2/1000
                    break
            }
            $("#singernote").text(note)
            $("#singermoney").text($currentmoney)
            r.selectItem[0].model = m
            r.selectItem[0].multiple = multiple
            r.selectItem[0].singernote = note
            r.selectItem[0].singermoney = $currentmoney
        }
        ,setSingerSelectItemNumber: function(number)
        {
            r.selectItem[0].selectNumber = number
        }
        ,setSelectItemHeighPrize: function()
        {
            playname = r.selectItem[0].playname
            if(playname=="混合组选")
            {
                r.selectItem[0].prize = r.bonus[0]['change']['组三']+'-'+ r.bonus[0]['change']['组六']+"|0|2"
            }
            else
            {
                r.selectItem[0].prize = r.bonus[0]['change'][playname]+"|0|2"
            }
        }
        ,setSelectItemLowPrize: function()
        {
            playname = r.selectItem[0].playname
            if(playname=="混合组选")
            {
                r.selectItem[0].prize = r.bonus[0]['common']['组三']+'-'+ r.bonus[0]['common']['组六']+"|"+ r.selectItem[0].bonusLevel + "|1"
            }
            else
            {
                if(playname.indexOf("不定位")==-1)
                    r.selectItem[0].prize = r.bonus[0]['common'][playname]+"|"+ r.selectItem[0].bonusLevel + "|1"
                else 
                    r.selectItem[0].prize = r.bonus[0]['common'][playname]+"|"+ r.selectItem[0].bdw_ret_point + "|1"
            }
        }
        ,mutiplechange: function()
        {
            var $mode;
            $singermoneyval = $("#singermoney")
            $("#J__selUnit").find('i').each(function(){
                if($(this).hasClass('on'))
                    $mode = $(this).attr('data-field')
            })
            $singernoteval = parseInt($("#singernote").text())
            $multipleval = parseInt($("input[name=multiple]").val())
            switch (parseInt($mode))
            {
                case 0:
                    $mulCurrentMoney = $singernoteval*$multipleval*2
                    break;
                case 1:
                    $mulCurrentMoney = $singernoteval*$multipleval*2/10
                    break;
                case 2:
                    $mulCurrentMoney = $singernoteval*$multipleval*2/100
                    break;
                case 3:
                    $mulCurrentMoney = $singernoteval*$multipleval*2/1000
                    break;
            }
            $singermoneyval.text($mulCurrentMoney)
            r.selectItem[0].multiple = $multipleval
            r.selectItem[0].singermoney = $mulCurrentMoney
        }
        ,init: function(data)
        {
            r.selectItem[0].playname = data.playname
            r.selectItem[0].showplayname = data.showplayname.replace(/(^\s*)|(\s*$)/g,"")
            r.selectItem[0].multiple = 1
            r.selectItem[0].model = 0
            r.selectItem[0].singernote = 0
            r.selectItem[0].singermoney = 0
            r.selectItem[0].selectNumber = ""
            if(typeof(r.bonus) != "undefined" )
            {
                ssc.setSelectItemHeighPrize()
            }
            $("#singernote").text(0)
            $("#singermoney").text(0)
            r.addItem=[]
        }
        ,initSetDefaultOption: function($id)
        {
            r.selectItem[0].position = ""
            $tabs = r.tabs
            $name = ""
            $tabs.forEach(function($item){
                if($item.id==$id)
                {
                    $name = $item.name
                }
            })
            r.selectItem[0].play = $name
            ssc.init({"playname": r.search[$id][0].games[0].alias,"showplayname":r.search[$id][0].games[0].name})
        }
        ,getSeparator: function()
        {
            $playname = r.selectItem[0].playname
            $play = r.selectItem[0].play
            $separator = ""
            $("#J__selNums li:eq(0)").find(".lottery-ball").each(function(){
                if(parseInt($(this).attr("data-val"))>9)
                    $separator = ","
            })
            if($playname.indexOf("龙虎")!=-1)
            {
                $separator = ","
            }
            return $separator
        }
        ,selectNumbe: function()
        {
            n = $('li','#J__selNums').length
            $select = new Array()
            $play = r.selectItem[0].play
            $playname = r.selectItem[0].playname

            // 返回分隔符
            $separator = ssc.getSeparator()
            $selectNumber = ''
            for(i=1; i<=n; i++)
            {
                $num = ""
                $("#J__selNums li:eq("+(i-1)+")").find(".lottery-ball").each(function(){
                    if($(this).hasClass("on"))
                    {
                        $num += $(this).attr("data-val")+$separator
                    }
                })
                if($separator==" ")
                    $num = $num.substring(0,$num.length-1)
                $selectNumber += $num + ","
            }
            if($playname.indexOf("龙虎")!=-1)
                $selectNumber = $selectNumber.substr(0,$selectNumber.length-2)
            else
                $selectNumber = $selectNumber.substr(0,$selectNumber.length-1)
            r.selectItem[0].selectNumber = $selectNumber
            for(i=1; i<=n; i++)
            {
                $temp = new Array();
                $count = $("#J__selNums li:eq("+(i-1)+")").find(".on").length
                $select.push($count)
            }
            window.select = $select;
        }
        ,calculateMultipleNote: function()
        {
            ssc.selectNumbe();
            p = $('.selected',"#game_search").attr("data-field");
            $func = ["直选复式","前二直选复式","后二直选复式","一码不定位"
            ,"后二大小单双","前二大小单双","三码大小单双"]
            $referenceFunc = new Object()
            $referenceFunc = '{' +
                '"直选和值":"zxhz","组三":"z3Func","组六":"z6Func","前二直选和值":"zxhzFunc",' +
                '"前二组选复式":"zxfsFunc","前二组选和值":"zxhFunc","后二直选和值":"zxhzFunc","后二组选复式":"zxfsFunc",' +
                '"后二组选和值":"zxhFunc","定位胆":"dwdFunc","二码不定位":"m2Func"}'
            $referenceArrFunc = JSON.parse($referenceFunc)
            if(jQuery.inArray(p,$func)!=-1)
            {
                $note = ssc.ElectionsPenthouse()
            }
            else
            {
                $note = ssc.setCalculateFuncHandler($referenceArrFunc[p])
            }
            ssc.calculateCommonUnitary($note)
        }
        ,ElectionsPenthouse: function()
        {
            $select = window.select
            $note = 1;
            for(i=0; i<$select.length; i++)
            {
                $note *= parseInt($select[i])
            }
            return $note
        }
        ,setCalculateFuncHandler: function(func)
        {
            return window[func]()
        }
        ,RndNum: function(n)
        {
            var rnd="";
            for(var i=0;i<n;i++)
                rnd+=Math.floor(Math.random()*10);
            return rnd;
        }
        ,checkSubmit: function()
        {
            if(r.selectItem[0].playname=="" || r.selectItem[0].selectNumber=="" || r.selectItem[0].singernote==0
                || r.selectItem[0].singermoney==0 || r.selectItem[0].model=="" || r.selectItem[0].prize=="" || parseInt(r.selectItem[0].multiple)<1
                || r.currentid=="" || r.selectItem[0].lid=="" || r.selectItem[0].singermoney==0)
            {
                return false
            }
                else
            {
                return true
            }
        }
    }
    win.ssc = ssc;
})(window);

$(function(){
    ssc.Loading()

    /* todo 选择奖金模式 */
    $(document).on('change',"select[name=prize]", function(){
        $n = r.selectItem[0]['playname']
        if($(this).val()==1)
        {
            ssc.setSelectItemLowPrize()
        }
        else
        {
            ssc.setSelectItemHeighPrize()
        }
    })

    /* todo 添加购买 */
    $(document).on("click",'a[data-method=addCartlist]',function(){
        ssc.addCartlist()
    })

    /*todo 删除购物车单条数据*/
    $(document).on('click', 'a[data-method="delete"]', function(e) {
        var $item = $(e.target).closest('li')
        var id = $(this).attr("data-field")
        $.dialog.close('*');
        layui.use('layer', function(layer){
        var index = layer.confirm('你确定要从购彩车中移除该订单吗?', {
            btn: ['确定','取消']
            ,anim:3
            ,btnAlign: 'c'
            }, function(){
                layer.close(index)
                $item.remove()
                r.orderItem.splice(id,1)
                ssc.calculateCartlist()
                $.dialog.close("*")
                return
            });
        })
    })

    /* todo 购物车全部清空 */
    $(document).on('click','a[data-method=cleanOrderItem]', function(){
        $.dialog.close('*');
        if($("li",cartlist).length==0) return false;
		layui.use('layer', function(layer){
        var index = layer.confirm('确定要清空购物车内容吗？', {
            btn: ['确定','取消']
            ,anim:3
            ,btnAlign: 'c'
            }, function(){
                layer.close(index)
                $("li",cartlist).remove()
                r.orderItem = []
                ssc.calculateCartlist()
                $.dialog.close("*")
                return
            });
        })
    })

    /* todo 倍数改变 */
    $('input[name=multiple]').on("blur",function(){
        $multipleval = parseInt($("input[name=multiple]").val())
        $multipleval = (isNaN($multipleval) || $multipleval==0) ? 1 : $multipleval;
        $("input[name=multiple]").val($multipleval)
        ssc.mutiplechange()
    })
    $("#diff").bind("click",function(){
        var multiple = parseInt($("input[name=multiple]").val())
        multiple = multiple + 1;
        $("input[name=multiple]").val(multiple)
        ssc.mutiplechange()
    })
    $("#plus").bind("click",function(){
        var multiple = parseInt($("input[name=multiple]").val())
        multiple = multiple - 1;
        multiple = (multiple<=0)?1:multiple;
        $("input[name=multiple]").val(multiple)
        ssc.mutiplechange()
    })

    /* todo 快速投注 */
    $('a[data-method="quickBet"]').on("click",function(){
        if(ssc.checkSubmit())
        {
            // 数据组合
            var t = new Date().getTime();
            var randkey = t + r.selectItem[0].lid + ssc.RndNum(6);
            randkey = "n*" + randkey + "*" + r.currentid + "*" + $("input[name=uid]").val();
            $selectData = r.selectItem[0].playname+':'+r.selectItem[0].selectNumber+":"+ r.selectItem[0].singernote+":1:"+r.selectItem[0].singermoney+
                ":"+ r.selectItem[0].model+":"+ r.selectItem[0].prize+":"+ r.selectItem[0].multiple+":"+ r.selectItem[0].position
            $data = "data="+$selectData+"&lottery_number_id="+r.currentid+"&is_add=0&lottery_id="+
                r.selectItem[0].lid+"&amount="+r.selectItem[0].singermoney+"&com="+randkey
                $('.quickBet').removeAttr('data-method')
            $('.quickBet').attr("disable","disable")
            $('.quickBet').html('<i class="icon-spinner icon-spin icon-1x"></i>提交中..')
            $.post("/Small",$data,function(info){
                $('.quickBet').removeAttr('disable')
                $('.quickBet').attr("data-method","quickBet")
                $('.quickBet').html('一键投注')
                 if(info==null) return
                if (info.status == 0 || info.status == "")
                {
                    $.dialog.close('*');
					layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        ssc.cleanSelectItemData()
                        ssc.addCartlistAfterHandler()
                        $('#cartlist>li').remove()
                        r.orderItem = []
                        ssc.calculateCartlist()
                    })
                }
                else
                {
                    $.dialog.close('*');
					layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        ssc.cleanSelectItemData()
                        ssc.addCartlistAfterHandler()
                        $('#cartlist>li').remove()
                        r.orderItem = []
                        ssc.calculateCartlist()
                        setTimeout(ssc.getBuyRecordItem,2000)
                        setTimeout(ssc.getMoney,2000)
                    })
                }
            },'json')
        }
        else
        {
            return false;
        }
    })

    /* todo 订单投注 */
    $('a[data-method=orderSubmit]').on("click",function(){
        $order = r.orderItem;
        var t = new Date().getTime();
        if($order.length==0) return false;
        var randkey = t + r.orderItem[0].lid + ssc.RndNum(6);
        randkey = "n*" + randkey + "*" + r.currentid + "*" + $("input[name=uid]").val();
        $selectData = ""
        $totalAmount = 0;
        for(var o in $order)
        {
            if($order[o].playname=="" || $order[o].selectNumber=="" || $order[o].singernote==0
                || $order[o].singermoney==0 || $order[o].model=="" || $order[o].prize=="" || parseInt($order[o].multiple)<1
                || r.currentid=="" || $order[o].lid=="" || $order[o].singermoney==0)
            {

            }
            else
            {
                $selectData += $order[o].playname+':'+$order[o].selectNumber+":"+ $order[o].singernote+":1:"+$order[o].singermoney+
                    ":"+ $order[o].model+":"+ $order[o].prize+":"+ $order[o].multiple + ":"+ $order[o].position + "||"
                $lid = $order[o].lid
                $totalAmount += parseFloat($order[o].singermoney*1000)
            }
        }
        if($selectData=="") return
        $selectData = $selectData.substr(0,$selectData.length-2);
        $totalAmount = parseFloat($totalAmount/1000)
        $data = "data="+$selectData+"&lottery_number_id="+r.currentid+"&is_add=0&lottery_id="+
            $lid+"&amount="+parseFloat($totalAmount)+"&com="+randkey
            $('.orderSubmit').removeAttr('data-method')
        $('.orderSubmit').attr("disable","disable")
        $('.orderSubmit').html('<i class="icon-spinner icon-spin icon-1x"></i>提交中..')
        $.post("/Small",$data,function(info){
            $('.orderSubmit').removeAttr('disable')
            $('.orderSubmit').attr("data-method","orderSubmit")
            $('.orderSubmit').html('立即投注')
            if (info.status == 0 || info.status == "")
            {
            	$.dialog.close('*');
				layui.use('layer', function(layer){
                    layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        $('#cartlist>li').remove()
	                    r.orderItem = []
	                    ssc.calculateCartlist()
                    })
                })
            }
            else
            {
                $.dialog.close('*');
                layui.use('layer', function(layer){
                    layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                    $('#cartlist>li').remove()
                    r.orderItem = []
                    ssc.calculateCartlist()
                    setTimeout(ssc.getBuyRecordItem,2000)
                    setTimeout(ssc.getMoney,2000)
                })
            }
        },'json')
    })
})