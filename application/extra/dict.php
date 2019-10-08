<?php

return [
    'Player' => [
        'resetPwd' => '玩家ID:{ct0}，密码:{ct1}，重置密码:{ct2}',
        'updatebank' => '玩家ID:{ct0}，银行卡:{ct1}，银行类型:{ct2}，用户:{ct3}',
        'updateRoleStatus' => '玩家ID:{ct0}',
        'deleteSuper' => '玩家ID:{ct0}',
        'addSuper' => '玩家ID:{ct0},比率{ct1}',
        'editSuper' => '玩家ID:{ct0},比率{ct1}',
        'addCutmoney' => '玩家ID:{ct0}，转账金额:{ct1},备注:{ct2}',
        'addTransfer' => '玩家ID:{ct0}，转账类型（备注：1:测试专用赠送,2:充值手工补发,3:转给超级玩家）:{ct1},转账金额:{ct2},备注:{ct3}',
    ],
    'Room'   => [
        'setPlayerRate' => '玩家ID:{ct0}，玩家胜率:{ct1}，控制时长:{ct2}秒',
        'setSocketRoomRate' => '房间ID:{ct0}，初始库存:{ct1}，当前库存:{ct2}',
        'setSocketRoomStorage' => '房间ID:{ct0}，库存值:{ct1}',
    ],
    'Playertrans' => [
        'agree' => '玩家ID:{ct0},订单ID:{ct1},审核人:{ct3},描述:{ct4},审核状态:{ct5}',
        'freeze' => '玩家ID:{ct0},订单ID:{ct1},审核人:{ct3},描述:{ct4}',
        'refuse' => '玩家ID:{ct0},订单ID:{ct1},审核人:{ct3},描述:{ct4}',
        'handle' => '玩家ID:{ct0},订单ID:{ct1},审核人:{ct3},描述:{ct4}',
    ],
   'Payment' => [
        'editChannel' => '通道ID:{ct0}，通道信息:{ct1}',
        'setChannelStatus' =>  '支付通道ID:{ct0}，类别:{ct1}，状态(类别为0时是开关，为1时是允许新玩家):{ct2}',

        //        'editChannel' => '通道ID:{ct0}，通道信息:{ct1}',
        'deleteChannel' => '通道ID:{ct0}',
    ],
    'Gamemanage' => [
        'deleteconfig' => '类型:{ct0}',
        'editconfig' => '类型:{ct0}，描述:{ct1}，值:{ct2}',
        'deleteWeixin' => '序号:{ct0}',
        'editWeixin' => '微信号:{ct0}，备注:{ct1}',
        'addWeixin' => '微信号:{ct0}，备注:{ct1}',
        'editTask' => '房间ID:{ct0}，游戏局数:{ct1},游戏奖励:{ct2},任务名:{ct3}',
        'addTask' => '房间ID:{ct0}，游戏局数:{ct1},游戏奖励:{ct2}',
        'setTaskStatus' => '配置ID:{ct0}，状态:{ct1}',
        'deleteIp' => 'ID:{ct0}',
        'blacklist' => 'IP/玩家ID:{ct0}',
    ],
    'Gamectrl' => [
        'setProfit' => '设置范围:{ct0}，游戏类型/房间ID:{ct1}，暗税税率:{ct2}，最大偏移值:{ct3}，当前库存:{ct4}，库存上限:{ct5}，库存下限{ct6}'
    ]

];