@extends('layouts.admin')
@section('title','New Laundry Transaction')
@section('content')

<style>
/* Adjustments to match exact screenshot for create page */
.new-tx-grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
.tx-form-card { background: var(--surface); border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 32px; }
.tx-input-group { margin-bottom: 24px; }
.tx-label { display: block; font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
.tx-control { width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 14px; color: #0f172a; outline: none; transition: all 0.2s; background: transparent; }
.tx-control:focus { border-color: #003366; }
.tx-control::placeholder { color: #94a3b8; }
.tx-icon-input { position: relative; }
.tx-icon-input svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; stroke: #94a3b8; fill: none; stroke-width: 2; }
.tx-icon-input .tx-control { padding-left: 42px; }

.action-btns { display: flex; justify-content: flex-end; gap: 16px; margin-top: 16px; align-items: center; }
.btn-cancel { font-size: 14px; font-weight: 600; color: #0f172a; background: none; border: none; padding: 10px 16px; cursor: pointer; }
.btn-create { font-size: 14px; font-weight: 600; color: #ffffff; background: #003366; border: none; border-radius: 8px; padding: 12px 24px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: background 0.2s; }
.btn-create:hover { background: #002244; }

.info-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px; }
.info-card { background: #eef2ff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
.info-card-icon { width: 40px; height: 40px; border-radius: 50%; background: #ffffff; display: flex; align-items: center; justify-content: center; color: #003366; flex-shrink: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
.info-card-title { font-size: 14px; font-weight: 600; color: #003366; margin-bottom: 2px; }
.info-card-sub { font-size: 12px; color: #64748b; }

.order-summary { background: #003366; border-radius: 16px; padding: 32px; color: #ffffff; position: relative; overflow: hidden; }
.order-summary::before { content:''; position:absolute; top:-50px; right:-50px; width:150px; height:150px; background:rgba(255,255,255,0.05); border-radius:50%; }
.os-title-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.os-title { font-size: 16px; font-weight: 600; }
.os-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 16px; color: rgba(255,255,255,0.8); }
.os-row.total { margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); align-items: flex-end; }
.os-row.total .os-label { font-size: 12px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px; }
.os-row.total .os-val { font-size: 32px; font-weight: 800; color: #ffffff; line-height: 1; }
.os-row.total .os-tax { font-size: 11px; color: rgba(255,255,255,0.6); display: block; text-align: right; margin-top: 4px; }

.loyalty-perk { background: rgba(255,255,255,0.1); border-radius: 8px; padding: 16px; margin-top: 24px; }
.lp-title { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; margin-bottom: 6px; }
.lp-desc { font-size: 12px; color: rgba(255,255,255,0.7); line-height: 1.4; }

.guarantee-card { position: relative; border-radius: 16px; overflow: hidden; margin-top: 24px; color: #ffffff; padding: 24px; height: 160px; display: flex; flex-direction: column; justify-content: flex-end; }
.guarantee-bg { position: absolute; inset: 0; background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); z-index: 0; }
.guarantee-content { position: relative; z-index: 1; }
.g-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
.g-desc { font-size: 12px; color: rgba(255,255,255,0.9); line-height: 1.4; }

.quick-check { background: #ffffff; border-radius: 16px; padding: 24px; margin-top: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
.qc-title { font-size: 13px; font-weight: 600; color: #64748b; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px; }
.qc-item { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #64748b; margin-bottom: 12px; }
.qc-item.checked { color: #0f172a; font-weight: 500; }
.qc-checkbox { width: 18px; height: 18px; border-radius: 4px; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; }
.qc-item.checked .qc-checkbox { background: #f8fafc; border-color: #cbd5e1; }
</style>

<div class="breadcrumb" style="font-size: 13px; margin-bottom: 16px; color: #64748b;">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span style="margin: 0 8px">›</span>
    <a href="{{ route('admin.transactions.index') }}">Transactions</a>
    <span style="margin: 0 8px">›</span>
    <span style="font-weight: 600; color: #0f172a;">New Entry</span>
</div>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 32px;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #003366; margin-bottom: 8px; letter-spacing: -0.5px;">New Laundry Transaction</h1>
        <p style="font-size: 14px; color: #64748b;">Register a new incoming order into the logistics system.</p>
    </div>
    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:12px 20px; display:flex; align-items:center; gap:16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <div style="width: 32px; height: 32px; border-radius: 50%; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        </div>
        <div>
            <div style="font-size:10px; color:#64748b; font-weight:700; letter-spacing:0.8px; margin-bottom: 2px;">CURRENT RATE</div>
            <div style="font-size:15px; font-weight:700; color:#0f172a;" id="currentRate">Rp 0 <span style="font-weight: 500; color: #64748b;">/ kg</span></div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.transactions.store') }}" id="txForm">
    @csrf
    
    @if($errors->any())
    <div style="background:#fef2f2; color:#991b1b; padding:16px; border-radius:8px; margin-bottom:24px; font-size:14px; border:1px solid #fca5a5;">
        {{ $errors->first() }}
    </div>
    @endif
    
    <input type="hidden" name="payment_status" value="lunas">

    <div class="new-tx-grid">
        <!-- Left Column -->
        <div>
            <div class="tx-form-card">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="tx-input-group">
                        <label class="tx-label">Customer Name</label>
                        <div class="tx-icon-input">
                            <svg><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" name="customer_name" class="tx-control" placeholder="e.g. Michael Chen" value="{{ old('customer_name') }}" required>
                        </div>
                    </div>
                    <div class="tx-input-group">
                        <label class="tx-label">Phone Number</label>
                        <div class="tx-icon-input">
                            <svg><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 9.81a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 0h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 7.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 14.92z"/></svg>
                            <input type="text" name="phone_number" class="tx-control" placeholder="+62 8xx-xxxx-xxxx" value="{{ old('phone_number') }}" required>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="tx-input-group">
                        <label class="tx-label">Service Type</label>
                        <div class="tx-icon-input">
                            <svg><path d="M20.2 7.8l-7.7 7.7-4-4-5.5 5.5"/><polyline points="16 7.8 20.2 7.8 20.2 12"/></svg>
                            <select name="service_id" class="tx-control" id="serviceSelect" required>
                                <option value="">Select Service...</option>
                                @foreach($services as $s)
                                <option value="{{ $s->id }}" data-price="{{ $s->price_per_kg }}" {{ old('service_id')==$s->id?'selected':'' }}>
                                    {{ $s->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tx-input-group">
                        <label class="tx-label">Weight (KG)</label>
                        <div class="tx-icon-input">
                            <svg><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            <input type="number" name="weight" id="weightInput" class="tx-control" placeholder="0.0" min="0.1" step="0.1" value="{{ old('weight') }}" required>
                            <span style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:600; color:#94a3b8;">KG</span>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="tx-input-group">
                        <label class="tx-label">Delivery Type</label>
                        <div class="tx-icon-input">
                            <svg><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            <select name="delivery_type" class="tx-control" id="deliveryTypeSelect" required style="padding-left: 42px;">
                                <option value="drop_off" {{ old('delivery_type') == 'drop_off' ? 'selected' : '' }}>Drop Off</option>
                                <option value="pickup_delivery" {{ old('delivery_type') == 'pickup_delivery' ? 'selected' : '' }}>Pickup & Delivery</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tx-input-group" id="addressGroup" style="display: none;">
                    <label class="tx-label">Pickup/Delivery Address</label>
                    <textarea name="address" id="addressInput" class="tx-control" rows="2" placeholder="Enter full address for pickup/delivery...">{{ old('address') }}</textarea>
                </div>

                <div class="tx-input-group">
                    <label class="tx-label">Special Instructions</label>
                    <textarea name="notes" class="tx-control" rows="3" placeholder="Add specific fabric care or delivery notes...">{{ old('notes') }}</textarea>
                </div>

                <div class="action-btns">
                    <a href="{{ route('admin.transactions.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-create">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Create Order
                    </button>
                </div>
            </div>

            <div class="info-cards">
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="info-card-title">Estimated Pickup</div>
                        <div class="info-card-sub">Today, after 5:00 PM</div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <div>
                        <div class="info-card-title">Storage Rack</div>
                        <div class="info-card-sub">Assigned: Sector B-12</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <div class="order-summary">
                <div class="os-title-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    <span class="os-title">Order Summary</span>
                </div>
                
                <div class="os-row">
                    <span>Base Rate (per kg)</span>
                    <span id="sumRate">Rp 0</span>
                </div>
                <div class="os-row">
                    <span>Calculated Weight</span>
                    <span id="sumWeight" style="font-weight: 600; color: #ffffff;">0.0 kg</span>
                </div>
                <div class="os-row">
                    <span>Service Charge</span>
                    <span>Rp 0</span>
                </div>
                
                <div class="os-row total">
                    <div>
                        <div class="os-label">Total Price</div>
                    </div>
                    <div>
                        <div class="os-val" style="display:flex; align-items:flex-end; gap:4px;">
                            <span style="font-size: 16px; padding-bottom: 3px;">Rp</span> 
                            <span id="sumTotal">0</span>
                        </div>
                        <span class="os-tax">+ Tax Included</span>
                    </div>
                </div>
                
                <div class="loyalty-perk">
                    <div class="lp-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="16 12 12 8 8 12"/><line x1="12" y1="16" x2="12" y2="8"/></svg>
                        Loyalty Perk Applied
                    </div>
                    <div class="lp-desc">Customer is eligible for 5% discount on next express order.</div>
                </div>
            </div>

            <div class="guarantee-card">
                <div class="guarantee-bg">
                    <div style="position:absolute; inset:0; background:radial-gradient(circle at 70% 30%, rgba(255,255,255,0.1) 0%, transparent 60%);"></div>
                </div>
                <div class="guarantee-content">
                    <div class="g-title">Quality Guaranteed</div>
                    <div class="g-desc">Our 12-point inspection ensures every garment is handled with precision.</div>
                </div>
            </div>

            <div class="quick-check">
                <div class="qc-title">Quick Check</div>
                <div class="qc-item checked">
                    <div class="qc-checkbox">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    Pockets checked for items
                </div>
                <div class="qc-item checked">
                    <div class="qc-checkbox">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    Fragile items separated
                </div>
                <div class="qc-item" style="opacity: 0.5;">
                    <div class="qc-checkbox"></div>
                    Pre-treatment applied
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function calcPrice() {
    const sel = document.getElementById('serviceSelect');
    const opt = sel.options[sel.selectedIndex];
    const price = parseFloat(opt?.dataset?.price || 0);
    const weight = parseFloat(document.getElementById('weightInput').value || 0);
    const total = price * weight;
    
    document.getElementById('currentRate').innerHTML = 'Rp ' + price.toLocaleString('id') + ' <span style="font-weight: 500; color: #64748b;">/ kg</span>';
    document.getElementById('sumRate').textContent = 'Rp ' + price.toLocaleString('id');
    document.getElementById('sumWeight').textContent = weight.toFixed(1) + ' kg';
    document.getElementById('sumTotal').textContent = total.toLocaleString('id');
}
document.getElementById('serviceSelect').addEventListener('change', calcPrice);
document.getElementById('weightInput').addEventListener('input', calcPrice);
calcPrice();

// Handle delivery type & address toggle
const deliverySelect = document.getElementById('deliveryTypeSelect');
const addressGroup = document.getElementById('addressGroup');
const addressInput = document.getElementById('addressInput');

function toggleAddress() {
    if (deliverySelect.value === 'pickup_delivery') {
        addressGroup.style.display = 'block';
        addressInput.required = true;
    } else {
        addressGroup.style.display = 'none';
        addressInput.required = false;
        if(!addressInput.defaultValue) {
            addressInput.value = '';
        }
    }
}
deliverySelect.addEventListener('change', toggleAddress);
toggleAddress();
</script>
@endpush
