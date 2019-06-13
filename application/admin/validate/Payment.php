<?php

namespace app\admin\validate;

use think\Validate;

class Payment extends Validate
{
    protected $rule = [
        //线下
        'id'     => 'require|number|gt:0',
        'amount' => 'require|number|gt:0',
    ];
    protected $message = [
        'id.require'     => 'ID不能为空',
        'id.number'      => 'ID格式有误',
        'id.gt'          => 'ID格式有误',
        'amount.require' => '金额不能为空',
        'amount.number'  => '金额格式有误',
        'amount.gt'      => '金额格式有误',
    ];
    protected $scene = [
        'editAmount' => [
            'id',
            'amount'
        ],
        'addAmount' => [
            'amount'
        ],
    ];

}
