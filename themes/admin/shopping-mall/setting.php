<?php
    if ( in('mode') == 'save' ) {
        update_option('delivery_fee_free_limit', in('delivery_fee_free_limit'), false);
        update_option('delivery_fee_price', in('delivery_fee_price'), false);
    }
?>
<h1>쇼핑몰 설정</h1>

<form action="post">
    <input type="hidden" name="page" value="admin.shopping-mall.setting">
    <input type="hidden" name="mode" value="save">
    <div class="mb-3">
        <label class="form-label">배송비 무료 결제 금액 하한가</label>
        <input type="number" class="form-control" name="delivery_fee_free_limit" v-model="delivery_fee_free_limit">
        <div id="emailHelp" class="form-text">총 결제 금액이 {{ delivery_fee_free_limit }}원 이상이면 무료입니다.</div>
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">배송비</label>
        <input type="number" name="delivery_fee_price" class="form-control" v-model="delivery_fee_price">
        <div id="emailHelp" class="form-text">총 결제 금액이 {{ delivery_fee_free_limit }}원 미만인 경우, 배송비가 {{ delivery_fee_price }}원입니다.</div>
    </div>
    <button type="submit" class="btn btn-primary">저장하기</button>
</form>


<script>
    const mixin = {
        data() {
            return {
                delivery_fee_free_limit: <?=get_option('delivery_fee_free_limit', 30000)?>,
                delivery_fee_price: <?=get_option('delivery_fee_price', 2500)?>,
            };
        },
        created() {
            console.log('setting created');
        }
    };
    later(function() {
        console.log(app.delivery_fee_price);
    })
</script>