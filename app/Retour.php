<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    protected $fillable = ['arrival_date',
                            'employee',
                            'customer_id',
                            'invoice_id',
                            'invoice_date',
                            'customer_name',
                            'product_quantity',
                            'invoice_quantity',
                            'product_name',
                            'invoice_name',
                            'open_products',
                            'credit_amount',
                            'invoice_total',
                            'invoice_price',
                            'total_orderamount',
                            'reason',
                            'comment',
                            'if_credited',
                            'contact',
                            'country_code',
                            'emailadress',
                            'carrier',
                            'nlcall_id',
                            'agent_name',
                            'agent_id',
                            'date_difference',
                            'exported',
                            'claim'
    ];

}
