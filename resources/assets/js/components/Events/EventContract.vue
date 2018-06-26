<template>
  <div class="col-md-12 no-padding contract">
    <div class="logo pull-right"></div>
    <section class="content no-padding">
      <div class="box box-widget">
        <div class="box-header with-border no-padding">
          <div class="col-xs-12">
            <label>
              <input type="radio" name="language" value="cn" v-model="language">
              <img src="/images/china.png" width="26" />
            </label>
            <label>
              <input type="radio" name="language" value="ja" v-model="language">
              <img src="/images/japan.png" width="26" />
            </label>
            <button class="btn btn-primary pull-right btn-xs" @click="close">返回</button>
          </div>
          <br /><br /><br />
          <div class="col-xs-4">{{ $t('contract.workingtime') }}: {{ event.event_date }}</div>
          <div class="col-xs-4"><h4 class="text-center">{{ $t('contract.title') }}</h4></div>
          <div class="col-xs-4">
            <div class="pull-right">
              <span>{{ $t('contract.issuedate') }}: {{ today }}</span><br />
              <span class="datepicker">{{ $t('contract.expiration') }}: {{ expiration }}</span>
            </div>
          </div>
          <div class="col-xs-12">
            <span class="underline">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </span>御中
          </div>
        </div>
        <div class="box-body">
          <div class="col-xs-12 no-padding">
            <div class="col-xs-7 no-padding">
              <div class="col-xs-10 border">
                <div class="col-xs-12 no-padding" v-if="event.details && event.details.from_address">
                  <b>{{ $t('event.fromAddress') }}:</b> {{ event.details.from_address }}
                </div>
                <div class="col-xs-12 no-padding" v-if="event.details && event.details.to_address">
                  <b>{{ $t('event.toAddress') }}:</b> {{ event.details.to_address }} 
                  <span v-if="event.details.to_elevator == 1"> {{ $t('event.elevator') }}</span>
                  <span v-else-if="event.details.to_elevator == 2"> {{ $t('event.stairs') }}</span>
                  <span v-else-if="event.details.to_elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
                  <span v-if="event.details.to_floor > 0 && event.details.to_floor < 10"> {{ event.details.to_floor }} {{ $t('event.floor') }}</span>
                  <span v-else-if="event.details.to_floor > 9"> {{ event.details.to_floor }}+ {{ $t('event.floor') }}</span>
                </div>
              </div>
              <div class="col-xs-12 border amount" v-if="event.amount">
                <h5>
                  <span v-if="event.amount" class="marginr3"><b>{{ $t('contract.amount') }}{{ event.amount | formatNumberJPY }}{{ $t('contract.taxincluded') }}</b></span>
                </h5>
              </div>
            </div>
            <div class="col-xs-4 border pull-right">
              子義引越センター<br />
              子義合同会社<br />
              03-5856-8281<br />
              担当:イトウ
              <div class="pull-right">
                <img src="/images/logo_w300.png" width="100" />
              </div>
            </div>
          </div>
          <div class="col-xs-12 border details">
            <div class="col-xs-6 no-padding" v-if="event.details.from_btype || event.details.from_elevator || event.details.from_floor">
              <b>{{ $t('event.fromAddress') }}:</b>
              <template v-if="event.details.from_btype">
                <span v-if="event.details.from_btype == 1">{{ $t('offer.buildingtype1') }}</span>
                <span v-else-if="event.details.from_btype == 2">{{ $t('offer.buildingtype2') }}</span>
                <span v-else-if="event.details.from_btype == 3">{{ $t('offer.buildingtype3') }}</span>
                <span v-else-if="event.details.from_btype == 4">{{ $t('offer.buildingtype4') }}</span>
                <span v-else-if="event.details.from_btype == 5">{{ $t('offer.buildingtype5') }}</span>
                <span v-else-if="event.details.from_btype == 6">{{ $t('offer.buildingtype6') }}</span>
              </template>
              <template v-if="event.details.from_elevator">
                <span v-if="event.details.from_elevator == 1"> {{ $t('event.elevator') }}</span>
                <span v-else-if="event.details.from_elevator == 2"> {{ $t('event.stairs') }}</span>
                <span v-else-if="event.details.from_elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
              </template>
              <template v-if="event.details.from_floor">
                <span v-if="event.details.from_floor > 0 && event.details.from_floor < 10"> {{ event.details.from_floor }} {{ $t('event.floor') }}</span>
                <span v-else-if="event.details.from_floor > 9"> {{ event.details.from_floor }}+ {{ $t('event.floor') }}</span>
              </template>
            </div>
            <div class="col-xs-6 no-padding" v-if="event.details.to_btype || event.details.to_elevator || event.details.to_floor">
              <b>{{ $t('event.toAddress') }}:</b>
              <template v-if="event.details.to_btype">
                <span v-if="event.details.to_btype == 1">{{ $t('offer.buildingtype1') }}</span>
                <span v-else-if="event.details.to_btype == 2">{{ $t('offer.buildingtype2') }}</span>
                <span v-else-if="event.details.to_btype == 3">{{ $t('offer.buildingtype3') }}</span>
                <span v-else-if="event.details.to_btype == 4">{{ $t('offer.buildingtype4') }}</span>
                <span v-else-if="event.details.to_btype == 5">{{ $t('offer.buildingtype5') }}</span>
                <span v-else-if="event.details.to_btype == 6">{{ $t('offer.buildingtype6') }}</span>
              </template>
              <template v-if="event.details.to_elevator">
                <span v-if="event.details.to_elevator == 1"> {{ $t('event.elevator') }}</span>
                <span v-else-if="event.details.to_elevator == 2"> {{ $t('event.stairs') }}</span>
                <span v-else-if="event.details.to_elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
              </template>
              <template v-if="event.details.to_floor">
                <span v-if="event.details.to_floor > 0 && event.details.to_floor < 10"> {{ event.details.to_floor }} {{ $t('event.floor') }}</span>
                <span v-else-if="event.details.to_floor > 9"> {{ event.details.to_floor }}+ {{ $t('event.floor') }}</span>
              </template>
            </div>
            <div class="col-xs-12 no-padding" v-if="total">
              <b>{{ $t('contract.total') }}:</b> {{ total }} {{ $t('contract.meter') }}
            </div>
            <div class="col-xs-12 no-padding" v-if="event.goods && event.goods.length > 0">
              <b>{{ $t('contract.aboutgoods') }}:</b>
              <span v-for="goodsname in event.goods"><i class="fa fa-cube"></i> {{ goodsname }} </span>
            </div>
            <div class="col-xs-12 no-padding" v-if="event.carefulnames && event.carefulnames.length > 0">
              <b>{{ $t('contract.careful') }}:</b>
              <span v-for="carefulname in event.carefulnames"><i class="fa fa-exclamation-triangle"></i> {{ carefulname }} </span>
            </div>
            <div class="col-xs-12 no-padding" v-if="event.details.phone">
              <b>{{ $t('contract.tel') }}:</b>
              {{ event.details.phone }}
            </div>
            <div class="col-xs-8 no-padding">
              {{ $t('contract.explanation1') }}<br />
              {{ $t('contract.explanation2') }}<br />
              {{ $t('contract.explanation3') }}<br />
              {{ $t('contract.explanation4') }}<br />
              {{ $t('contract.explanation5') }}<br />
              {{ $t('contract.explanation6') }}<br />
              {{ $t('contract.explanation7') }}
            </div>
          </div>
          
          <div class="col-xs-6 no-padding">
            {{ $t('contract.payee') }}<br />
            ゆうちょ銀行<br />
            記号:10010 番号　5221385<br />
            コヨシ（ド<br /><br />
            店名00八 店番008 普通<br />
          </div>
          <div class="col-xs-6 no-padding" v-if="event.comments && event.comments.length > 0">
            <span v-for="comment in event.comments">{{ comment.content }}<br /></span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import moment from 'moment'
import datetimepicker from 'jquery-datetimepicker'

export default {
  props: ['eventdata'],
  data() {
    return {
      language: "cn",
      hasFile: false,
      showImgs: false,
      today: moment().format('YYYY年MM月DD日'),
      expiration: moment().add(7, 'd').format('YYYY年MM月DD日'),
      event: {
        details: {}
      },
      total: 0
    }
  },
  mounted() {
    this.event = this.eventdata[0];
    var totalarray = JSON.parse(this.event.total);
    if(totalarray) {
      for(var i in totalarray) {
        if(totalarray[i] == 66) {
          this.total += 3;
        } else if(totalarray[i] == 67) {
          this.total += 5;
        } else if(totalarray[i] == 68) {
          this.total += 7.5;
        } else if(totalarray[i] == 69) {
          this.total += 11;
        } else if(totalarray[i] == 70) {
          this.total += 20;
        } else if(totalarray[i] == 71) {
          this.total += 15;
        } else if(totalarray[i] == 76) {
          this.total += 10;
        }
      }
    }
    $.datetimepicker.setLocale('zh')
    $('.datepicker').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
    $('.datepicker').on('change', (e) => {
      if($(e.target).val() != ''){
        this.expiration = moment($(e.target).val()).format('YYYY年MM月DD日')
      } else {
        this.expiration = moment().add(7, 'd').format('YYYY年MM月DD日');
      }
    })
  },
  methods: {
    close() {
      this.$i18n.locale = 'cn';
      $('.marginb5').show();
      $('.content-header').show();
      this.$emit('close');
    },
    formatDateWithWeekname(date) {
      var weekname = "";
      if(this.$i18n.locale == 'cn') {
        moment.locale('cn');
      } else {
        moment.locale('ja');
      }
      weekname = moment(date).format('dddd');

      return moment(date).format('YYYY年MM月DD日') + ' ' + weekname;
    }
  },
  filters: {
		formatNumberJPY(number) {
			if(number) {
				return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
			}
		}
	},
  watch: {
    language(lang) {
      this.$i18n.locale = lang;
    }
  }
}
</script>

<style>
.contract {
  font-size: 12px;
}
.content {
  padding-bottom: 200px!important;
}
.details {
  margin-bottom: 50px!important;
}
.col-xs-4 {
  padding:0 10px;
}
.no-padding {
  padding: 3px 0 !important;
}
.label, .marginr3 {
  margin-right: 3px;
  font-weight: 100;
  line-height: 15px;
  display: inline-table;
  margin: 1px;
}
.underline {
  border-bottom: 1px solid #000;
  width: 60px;
}
.border {
  border: 2px solid #000;
  padding: 5px;
}
.amount {
  margin-top: 5px;
}
</style>