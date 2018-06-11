<template>
  <div class="col-md-12">
    
    <div class="logo pull-right"></div>
    
    
    <section class="content no-padding">
      <div class="box box-widget">
        <div class="box-header with-border">
          <div class="col-xs-12">
            <label>
              <input type="radio" name="language" value="cn" v-model="language">
              <img src="/images/china.png" width="26" />
            </label>
            <label>
              <input type="radio" name="language" value="ja" v-model="language">
              <img src="/images/japan.png" width="26" />
            </label>
            <div class="pull-right">
              <img src="/images/logo_w300.png" width="100" />
            </div>
          </div>
          <div class="col-xs-12">
            <div class="pull-right">
              <h5>{{ $t('contract.issuedate') }}: {{ today }}</h5>
              <h5 class="datepicker">{{ $t('contract.expiration') }}: {{ expiration }}</h5>
            </div>
          </div>
          <h3 class="text-center">{{ $t('contract.title') }}</h3>
        </div>
        <div class="box-body">
          <div class="col-xs-12 no-padding">
            {{ $t('contract.workingtime') }}: {{ formatDateWithWeekname(event.event_date) }}
            <span v-if="event.apm == 1">{{ $t('contract.morning') }}</span>
            <span v-else-if="event.apm == 2">{{ $t('contract.afternoon') }}</span>
            <span v-else-if="event.apm == 3">{{ $t('contract.night') }}</span>
            <span v-else-if="event.apm == 4">{{ $t('contract.allday') }}</span>
            <span v-else>
              <span v-if="event.start_time">{{ event.start_time | formatTime(event.event_date) }}</span>
              〜
              <span v-if="event.end_time">{{ event.end_time | formatTime(event.event_date) }}</span>
            </span>
          </div>
          <div class="col-xs-12 no-padding" v-if="event.from.address">
            <b>{{ $t('event.fromAddress') }}:</b> {{ event.from.address }} 
            <span v-if="event.from.elevator == 1"> {{ $t('event.elevator') }}</span>
            <span v-else-if="event.from.elevator == 2"> {{ $t('event.stairs') }}</span>
            <span v-else-if="event.from.elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
            <span v-if="event.from.floor > 0 && event.from.floor < 10"> {{ event.from.floor }} {{ $t('event.floor') }}</span>
            <span v-else-if="event.from.floor > 9"> {{ event.from.floor }}+ {{ $t('event.floor') }}</span>
          </div>
          <div class="col-xs-12 no-padding" v-if="event.to.address">
            <b>{{ $t('event.toAddress') }}:</b> {{ event.to.address }} 
            <span v-if="event.to.elevator == 1"> {{ $t('event.elevator') }}</span>
            <span v-else-if="event.to.elevator == 2"> {{ $t('event.stairs') }}</span>
            <span v-else-if="event.to.elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
            <span v-if="event.to.floor > 0 && event.to.floor < 10"> {{ event.to.floor }} {{ $t('event.floor') }}</span>
            <span v-else-if="event.to.floor > 9"> {{ event.to.floor }}+ {{ $t('event.floor') }}</span>
          </div>
          <div class="col-xs-12 no-padding" v-if="event.total">
            <b>{{ $t('contract.total') }}:</b> 
            <span v-if="event.total == 66"> {{ $t('contract.totalt1') }}</span>
            <span v-else-if="event.total == 67"> {{ $t('contract.totalt2') }}</span>
            <span v-else-if="event.total == 68"> {{ $t('contract.totalt3') }}</span>
            <span v-else-if="event.total == 69"> {{ $t('contract.totalt4') }}</span>
            <span v-else-if="event.total == 71"> {{ $t('contract.totalt5') }}</span>
            <span v-else-if="event.total == 70"> {{ $t('contract.totalt6') }}</span>
          </div>
          <h5 v-if="event.carefulnames && event.carefulnames.length > 0">
            <span v-for="carefulname in event.carefulnames" class="label label-danger"><i class="fa fa-exclamation-triangle"></i> {{ carefulname }}</span>
          </h5>
          <h5 v-if="(event.goods && event.goods.length > 0) || (event.totalname && event.totalname.length > 0)">
            <span v-if="event.totalname" class="label label-primary"><i class="fa fa-cubes"></i> {{ event.totalname }}</span>
            <span v-for="goodsname in event.goods" class="label label-primary"><i class="fa fa-cube"></i> {{ goodsname }}</span>
          </h5>
          <h4>
            <span v-if="event.amount" class="marginr3"><b>{{ $t('productlist.total') }}{{ event.amount | formatNumberJPY }}</b></span>
          </h4>
          <div class="col-xs-6 no-padding">
            {{ $t('contract.payee') }}<br />
            ゆうちょ銀行<br />
            記号:10010 番号　5221385<br />
            コヨシ（ド<br /><br />
            店名00八 店番008 普通<br />
          </div>
          <div class="col-xs-6 no-padding">
            子義引越センター<br />
            子義合同会社<br />
            03-5856-8281<br />
            担当:イトウ
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
  data() {
    return {
      language: "cn",
      hasFile: false,
      showImgs: false,
      today: moment().format('YYYY年MM月DD日'),
      expiration: moment().add(7, 'd').format('YYYY年MM月DD日'),
      event: {
        id: "",
        eventdate: "",
        apm: "",
        from: {
          time: "",
          address: "",
          elevator: "",
          floors: "0",
        },
        to: {
          time: "",
          address: "",
          elevator: "",
          floors: "0",
        },
        deposit: {
          rmb: 0,
          jpy: 0
        },
        carefulnames: [],
        worktype: "",
        aboutgoods: "",
        careful: "",
        total: "",
        truck: "",
        wechat: "",
        phone: "",
        partner: null,
        amount: null,
        product_list_id: "",
        order_id: "",
        files: [],
        filethumbs: [],
        productlistimgs: [],
        comment: ""
      }
    }
  },
  mounted() {
    if(this.$route.params.eventid){
      this.getEvent(this.$route.params.eventid);
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
    getEvent(eventid) {
      this.$http({
        url: '/admin/event/showbycontract/' + eventid,
        method: 'GET'
      }).then(res =>  {
        this.event.id = eventid;
        this.event.worktype = JSON.parse(res.data.types);
        this.event.aboutgoods = JSON.parse(res.data.details.aboutgoods);
        this.event.careful = JSON.parse(res.data.details.carefully);
        this.event.truck = JSON.parse(res.data.details.trucks);
        this.event.total = res.data.total;
        this.event.amount = res.data.amount;
        this.event.eventdate = res.data.event_date;
        this.event.from.address = res.data.details.from_address;
        this.event.to.address = res.data.details.to_address;
        this.event.apm = res.data.apm;
        this.event.carefulnames = res.data.carefulnames;
        if(res.data.apm == 5) {
          this.event.from.time = res.data.start_time;
          this.event.to.time = res.data.end_time;
        }
        this.event.from.floors = res.data.details.from_floor;
        this.event.from.elevator = res.data.details.from_elevator;
        this.event.to.floors = res.data.details.to_floor;
        this.event.to.elevator = res.data.details.to_elevator;
      })
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
    },
    shopImg(img) {
      if(img.substring(0, 4) === 'http'){
        return true
      } else {
        return false
      }
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
</style>