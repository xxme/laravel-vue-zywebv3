<template>
    <div class="box" id="statistics">
        <!-- statistics -->
        <div class="box-header">
            <h3 class="box-title"><button type="button" class="btn btn-sm btn-default" @click="$emit('closeform')"><i class="fa fa-arrow-left"></i></button> {{ showYm }}{{ $t('finance.finance') }}</h3>
        </div>
        <table class="table table-striped">
            <tbody>
                <tr v-if="fee.finance.total || fee.finance.expenditure">
                    <td style="width: 50%">{{ $t('finance.total') }}:{{ fee.finance.total | formatNumberJPY }}</td>
                    <td>{{ $t('finance.expenditure') }}:{{ fee.finance.expenditure | formatNumberJPY }}</td>
                </tr>
                <tr v-if="fee.finance.zcgaosu || fee.finance.zcjiayou">
                    <td>{{ $t('finance.zcgaosu') }}:{{ fee.finance.zcgaosu | formatNumberJPY }}</td>
                    <td>{{ $t('finance.zcjiayou') }}:{{ fee.finance.zcjiayou | formatNumberJPY }}</td>
                </tr>
                <tr v-if="fee.finance.zctingche || fee.finance.zccanyin">
                    <td>{{ $t('finance.zctingche') }}:{{ fee.finance.zctingche | formatNumberJPY }}</td>
                    <td>{{ $t('finance.zccanyin') }}:{{ fee.finance.zccanyin | formatNumberJPY }}</td>
                </tr>
                <tr v-if="fee.finance.zcmaihuo || fee.finance.zcother">
                    <td>{{ $t('finance.zcmaihuo') }}:{{ fee.finance.zcmaihuo | formatNumberJPY }}</td>
                    <td>{{ $t('finance.zcother') }}:{{ fee.finance.zcother | formatNumberJPY }}</td>
                </tr>
                <tr v-if="fee.finance.fxjpy || fee.finance.fxrmb">
                    <td>{{ $t('event.fxjpy') }}:{{ fee.finance.fxjpy | formatNumberJPY }}</td>
                    <td>{{ $t('event.fxrmb') }}:{{ fee.finance.fxrmb | formatNumberJPY }}</td>
                </tr>
                <tr>
                    <td v-if="fee.finance.undone" colspan="2">{{ $t('finance.undone') }}:{{ fee.finance.undone | formatNumberJPY }}</td>
                </tr>
            </tbody>
        </table>
        <div class="box-header">
            <h3 class="box-title">{{ showYm }}{{ $t('finance.proceeds') }}</h3>
        </div>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th style="width: 50px">{{ $t('finance.ranking') }}</th>
                    <th style="width: 150px">{{ $t('finance.name') }}</th>
                    <th>{{ $t('finance.amount') }}</th>
                    <th>{{ $t('finance.completed') }}</th>
                    <th style="width: 80px">{{ $t('finance.percentage') }}</th>
                </tr>
            </tbody>
        </table>
        <!-- ./statistics -->
    </div>
</template>
<script>
import Vue from 'vue'

export default {
    props: ['fee', 'showYm'],
    mounted() {
        var self = this;
    },
    data() {
        return {
        }
    },
    filters: {
        formatNumberJPY(number) {
            if(number) {
                return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
            } else {
                return '0';
            }
        }
    }
}
</script>
