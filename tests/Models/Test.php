<?php

class Test extends Illuminate\Database\Eloquent\Model
{
  protected $table = 'tests';

  protected $guarded = ['id', 'flag'];
  protected $casts = [
    'id' => 'int',
    'flag' => 'bool',
  ];
}
