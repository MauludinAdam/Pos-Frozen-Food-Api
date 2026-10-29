<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        'dashboard' => [
            'menu'
        ],

        'category' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete'
        ],

        'satuan' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'product' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete'
        ],

        'user' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete'
        ],

        'customer' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'transaction' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete'
        ],

        'detail-transaction' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'payment-transaction' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete'
        ],

        'stock-transaction' => [
            'menu',
            'list',
            'create',
            'delete',
        ],

        'stock-transaction-item' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'transaction-return' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'purchase-order' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'purchase-order-item' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'supplier'  => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ],

        'good-receipt' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete'
        ],

        'good-receipt-item' => [
            'menu',
            'list',
            'create',
            'edit',
            'delete',
        ]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->permissions as $key => $value) {

            foreach($value as $permission){
                Permission::firstOrCreate([
                    'name'  => $key . '-' . $permission,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Role Admin
        $admin = Role::findByName('admin', 'web');
        $admin->syncPermissions(Permission::all());

        // Role Kasir
        $kasir = Role::findByName('kasir','web');
        $kasir->syncPermissions([
            'dashboard-menu',
            'transaction-menu',
            'transaction-list',
            'transaction-create',

            'detail-transaction-list',
            'detail-transaction-create',

            'payment-transaction-list',
            'payment-transaction-create',
        ]);

        // Role Admin Gudang
        $admin_gudang = Role::findByName('admin gudang', 'web');
        $admin_gudang->syncPermissions([
            'dashboard-menu',
            'stock-transaction-menu',
            'stock-transaction-list',
            'stock-transaction-create',

            'stock-transaction-item-list',
            'stock-transaction-item-create',

            'transaction-return-menu',
            'transaction-return-list',

            'supplier-menu',
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',

            'purchase-order-menu',
            'purchase-order-list',
            'purchase-order-create',
            'purchase-order-edit',
            'purchase-order-delete',

            'purchase-order-item-menu',
            'purchase-order-item-list',
            'purchase-order-item-create',
            'purchase-order-item-edit',
            'purchase-order-item-delete',

            'good-receipt-menu',
            'good-receipt-list',
            'good-receipt-create',
            'good-receipt-edit',
            'good-receipt-delete',

            'good-receipt-item-menu',
            'good-receipt-item-list',
            'good-receipt-item-create',
            'good-receipt-item-edit',
            'good-receipt-item-delete',
        ]);
    }
}
