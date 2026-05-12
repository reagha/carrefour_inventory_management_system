<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\BranchRequest;
use App\Models\BranchRequestItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // ==========================================
        // 1. SEED BRANCHES
        // ==========================================
        $b_oasis   = Branch::create(['name' => 'Oasis Mall', 'location' => 'Kampala CBD']);
        $b_lugogo  = Branch::create(['name' => 'Lugogo Mall', 'location' => 'Lugogo Bypass']);
        $b_acacia  = Branch::create(['name' => 'Acacia Mall', 'location' => 'Kisementi']);
        $b_entebbe = Branch::create(['name' => 'Victoria Mall', 'location' => 'Entebbe']);

        // ==========================================
        // 2. SEED USERS
        // ==========================================
        $admin = User::create(['name' => 'System Admin', 'email' => 'admin@warehouse.com', 'password' => $password, 'role' => 'admin']);
        $procurement = User::create(['name' => 'Sarah Procurement', 'email' => 'procurement@warehouse.com', 'password' => $password, 'role' => 'procurement']);
        $auditor = User::create(['name' => 'Jane Auditor', 'email' => 'auditor@warehouse.com', 'password' => $password, 'role' => 'auditor']);
        
        // Warehouse Team
        $warehouse1 = User::create(['name' => 'Mike Warehouse', 'email' => 'warehouse1@warehouse.com', 'password' => $password, 'role' => 'warehouse']);
        $warehouse2 = User::create(['name' => 'David Loader', 'email' => 'warehouse2@warehouse.com', 'password' => $password, 'role' => 'warehouse']);

        // Branch Managers
        $mgr_oasis = User::create(['name' => 'John Oasis', 'email' => 'oasis@warehouse.com', 'password' => $password, 'role' => 'branch_manager', 'branch_id' => $b_oasis->id]);
        $mgr_lugogo = User::create(['name' => 'Mary Lugogo', 'email' => 'lugogo@warehouse.com', 'password' => $password, 'role' => 'branch_manager', 'branch_id' => $b_lugogo->id]);
        $mgr_acacia = User::create(['name' => 'Peter Acacia', 'email' => 'acacia@warehouse.com', 'password' => $password, 'role' => 'branch_manager', 'branch_id' => $b_acacia->id]);

        // ==========================================
        // 3. SEED SUPPLIERS
        // ==========================================
        $sup_kakira  = Supplier::create(['name' => 'Kakira Sugar Ltd', 'contact_email' => 'sales@kakirasugar.com', 'contact_phone' => '+256 700 111111']);
        $sup_coke    = Supplier::create(['name' => 'Coca-Cola Beverages', 'contact_email' => 'orders@ccba.co.ug', 'contact_phone' => '+256 700 222222']);
        $sup_mukwano = Supplier::create(['name' => 'Mukwano Industries', 'contact_email' => 'sales@mukwano.com', 'contact_phone' => '+256 700 333333']);
        $sup_jesa    = Supplier::create(['name' => 'Jesa Farm Dairy', 'contact_email' => 'supply@jesa.co.ug', 'contact_phone' => '+256 700 444444']);
        $sup_nile    = Supplier::create(['name' => 'Nile Breweries', 'contact_email' => 'orders@ug.ab-inbev.com', 'contact_phone' => '+256 700 555555']);

        // ==========================================
        // 4. SEED PRODUCTS
        // ==========================================
        $p_sugar1kg = Product::create(['sku' => 'KAK-SUG-1KG', 'name' => 'Kakira Sugar 1kg', 'supplier_id' => $sup_kakira->id, 'unit_cost' => 3500, 'quantity_in_warehouse' => 1200, 'reorder_level' => 200]);
        $p_sugar50kg = Product::create(['sku' => 'KAK-SUG-50KG', 'name' => 'Kakira Sugar 50kg Sack', 'supplier_id' => $sup_kakira->id, 'unit_cost' => 150000, 'quantity_in_warehouse' => 45, 'reorder_level' => 10]);
        
        $p_coke = Product::create(['sku' => 'COKE-500ML', 'name' => 'Coca-Cola 500ml', 'supplier_id' => $sup_coke->id, 'unit_cost' => 1200, 'quantity_in_warehouse' => 800, 'reorder_level' => 100]);
        $p_water = Product::create(['sku' => 'RWEN-500ML', 'name' => 'Rwenzori Water 500ml', 'supplier_id' => $sup_coke->id, 'unit_cost' => 800, 'quantity_in_warehouse' => 2000, 'reorder_level' => 500]);
        
        $p_oil = Product::create(['sku' => 'MUK-OIL-1L', 'name' => 'Mukwano Cooking Oil 1L', 'supplier_id' => $sup_mukwano->id, 'unit_cost' => 6500, 'quantity_in_warehouse' => 350, 'reorder_level' => 50]);
        $p_soap = Product::create(['sku' => 'MUK-SOAP-1KG', 'name' => 'White Star Soap 1kg', 'supplier_id' => $sup_mukwano->id, 'unit_cost' => 4000, 'quantity_in_warehouse' => 600, 'reorder_level' => 100]);
        
        $p_milk = Product::create(['sku' => 'JES-MILK-1L', 'name' => 'Jesa Fresh Milk 1L', 'supplier_id' => $sup_jesa->id, 'unit_cost' => 2800, 'quantity_in_warehouse' => 150, 'reorder_level' => 50]); // Low stock!
        $p_yoghurt = Product::create(['sku' => 'JES-YOG-500ML', 'name' => 'Jesa Yoghurt Vanilla 500ml', 'supplier_id' => $sup_jesa->id, 'unit_cost' => 3000, 'quantity_in_warehouse' => 80, 'reorder_level' => 30]);
        
        $p_nile = Product::create(['sku' => 'NIL-SPEC-500ML', 'name' => 'Nile Special 500ml', 'supplier_id' => $sup_nile->id, 'unit_cost' => 2500, 'quantity_in_warehouse' => 1000, 'reorder_level' => 200]);
        $p_club = Product::create(['sku' => 'CLUB-PILS-500ML', 'name' => 'Club Pilsener 500ml', 'supplier_id' => $sup_nile->id, 'unit_cost' => 2500, 'quantity_in_warehouse' => 900, 'reorder_level' => 200]);

        // ==========================================
        // 5. SEED PURCHASE ORDERS (Inbound)
        // ==========================================
        // PO 1: Historical (Received) - Milk & Yoghurt
        $po1 = PurchaseOrder::create(['supplier_id' => $sup_jesa->id, 'procurement_manager_id' => $procurement->id, 'total_amount' => 860000, 'status' => 'received', 'received_by' => $warehouse1->id, 'received_at' => now()->subDays(5)]);
        PurchaseOrderItem::create(['purchase_order_id' => $po1->id, 'product_id' => $p_milk->id, 'quantity' => 200, 'unit_cost' => 2800, 'subtotal' => 560000]);
        PurchaseOrderItem::create(['purchase_order_id' => $po1->id, 'product_id' => $p_yoghurt->id, 'quantity' => 100, 'unit_cost' => 3000, 'subtotal' => 300000]);

        // PO 2: Approved (Waiting for delivery truck) - Mukwano Soap & Oil
        $po2 = PurchaseOrder::create(['supplier_id' => $sup_mukwano->id, 'procurement_manager_id' => $procurement->id, 'total_amount' => 1450000, 'status' => 'approved']);
        PurchaseOrderItem::create(['purchase_order_id' => $po2->id, 'product_id' => $p_oil->id, 'quantity' => 100, 'unit_cost' => 6500, 'subtotal' => 650000]);
        PurchaseOrderItem::create(['purchase_order_id' => $po2->id, 'product_id' => $p_soap->id, 'quantity' => 200, 'unit_cost' => 4000, 'subtotal' => 800000]);

        // PO 3: Pending (Procurement just drafted it) - Kakira Sugar
        $po3 = PurchaseOrder::create(['supplier_id' => $sup_kakira->id, 'procurement_manager_id' => $procurement->id, 'total_amount' => 1750000, 'status' => 'pending']);
        PurchaseOrderItem::create(['purchase_order_id' => $po3->id, 'product_id' => $p_sugar1kg->id, 'quantity' => 500, 'unit_cost' => 3500, 'subtotal' => 1750000]);

        // ==========================================
        // 6. SEED BRANCH REQUESTS (Outbound)
        // ==========================================
        // Request 1: Dispatched to Oasis Mall (Historically)
        $req1 = BranchRequest::create(['branch_id' => $b_oasis->id, 'branch_manager_id' => $mgr_oasis->id, 'status' => 'dispatched', 'dispatched_by' => $warehouse1->id, 'dispatched_at' => now()->subDays(2)]);
        BranchRequestItem::create(['branch_request_id' => $req1->id, 'product_id' => $p_coke->id, 'quantity' => 200]);
        BranchRequestItem::create(['branch_request_id' => $req1->id, 'product_id' => $p_water->id, 'quantity' => 500]);

        // Request 2: Pending (Lugogo Mall needs beer & sugar)
        $req2 = BranchRequest::create(['branch_id' => $b_lugogo->id, 'branch_manager_id' => $mgr_lugogo->id, 'status' => 'pending']);
        BranchRequestItem::create(['branch_request_id' => $req2->id, 'product_id' => $p_nile->id, 'quantity' => 300]);
        BranchRequestItem::create(['branch_request_id' => $req2->id, 'product_id' => $p_sugar1kg->id, 'quantity' => 150]);

        // Request 3: Pending (Acacia Mall needs dairy)
        $req3 = BranchRequest::create(['branch_id' => $b_acacia->id, 'branch_manager_id' => $mgr_acacia->id, 'status' => 'pending']);
        BranchRequestItem::create(['branch_request_id' => $req3->id, 'product_id' => $p_milk->id, 'quantity' => 50]);
        BranchRequestItem::create(['branch_request_id' => $req3->id, 'product_id' => $p_yoghurt->id, 'quantity' => 20]);
    }
}