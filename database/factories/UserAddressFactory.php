<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserAddress;
use Faker\Generator as Faker;

$factory->define(UserAddress::class, function (Faker $faker) {
    $addresses = [
        ["北京市", "市辖区", "朝阳区"],
        ["上海市", "市辖区", "浦东新区"],
        ["河北省", "石家庄市", "裕华区"],
        ["河南省", "郑州市", "二七区"],
        ["江苏省", "南京市", "浦口区"],
        ["江苏省", "苏州市", "相城区"],
        ["广东省", "深圳市", "福田区"],
    ];
    $address = $faker->randomElement($addresses);

    $userIds = \App\Models\User::query()->pluck('id')->toArray();
    return [
        'user_id'       => array_rand($userIds),
        'province'      => $address[0],
        'city'          => $address[1],
        'district'      => $address[2],
        'address'       => sprintf('第%d街道第%d号', $faker->randomNumber(2), $faker->randomNumber(3)),
        'zip'           => $faker->postcode,
        'contact_name'  => $faker->name,
        'contact_phone' => $faker->phoneNumber,
    ];
});
