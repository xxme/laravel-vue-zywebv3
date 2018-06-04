<template>
  <div class="col-md-12">
    <div class="pull-right">
      <label>
        <input type="radio" name="language" value="cn" v-model="language">
        <img src="/images/china.png" width="26" />
      </label>
      <label>
        <input type="radio" name="language" value="ja" v-model="language">
        <img src="/images/japan.png" width="26" />
      </label>
    </div>
    <h3>{{ $t('contract.title') }}</h3>
    <section class="content">
      <div class="box box-widget">
        <div class="box-header with-border">
          {{ $t('contract.workingtime') }} {{ formatDateWithWeekname(event.event_date) }} 
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
        <div class="box-body">
          <h5 v-if="event.typenames && event.typenames.length > 0">
            <span v-if="event.trucks && event.trucks.length > 0"><span v-for="truckname in event.trucks" class="label label-success"><i class="fa fa-truck"></i> {{ truckname }}</span></span>
            <span v-for="typename in event.typenames" class="label label-success"><i class="fa fa-tag"></i> {{ typename }}</span>
          </h5>
          <h5 v-if="event.carefulnames && event.carefulnames.length > 0">
            <span v-for="carefulname in event.carefulnames" class="label label-danger"><i class="fa fa-exclamation-triangle"></i> {{ carefulname }}</span>
          </h5>
          <h5 v-if="(event.goods && event.goods.length > 0) || (event.totalname && event.totalname.length > 0)">
            <span v-if="event.totalname" class="label label-primary"><i class="fa fa-cubes"></i> {{ event.totalname }}</span>
            <span v-for="goodsname in event.goods" class="label label-primary"><i class="fa fa-cube"></i> {{ goodsname }}</span>
          </h5>
          <h4>
            <span v-if="event.amount" class="marginr3">{{ event.amount | formatNumberJPY }}</span>
            <small>
              <span v-if="event.partner" class="marginr3"><i class="fa fa-male"></i> {{ event.partner }}</span>
              <span v-if="event.product_list_id && event.productlist" class="marginr3"><i class="fa fa-shopping-cart"></i><router-link :to="'/admin/productlist/'+event.product_list_id+'/edit'"> #{{ event.product_list_id }} {{ formatNumberJPY(event.productlist.price) }}</router-link></span>
              <span v-if="event.order_id" class="marginr3"><i class="fa fa-comments-o"></i> #{{ event.order_id }}</span>
              <span v-if="event.details && event.details.phone" class="marginr3"><i class="fa fa-phone"></i>  <a :href="'tel:' + event.details.phone">{{ event.details.phone }}</a></span>
              <span v-if="event.details && event.details.wechat"><i class="fa fa-wechat"></i>  {{ event.details.wechat }}</span>
            </small>
          </h4>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import moment from 'moment'
import baguetteBox from 'baguettebox.js'

export default {
  data() {
    return {
      language: "cn",
      hasFile: false,
      showImgs: false,
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
  },
  methods: {
    getEvent(eventid) {
      this.$http({
        url: '/admin/event/showbycontract/' + eventid,
        method: 'GET'
      }).then(res =>  {
        console.log(res);
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
        this.event.phone = res.data.details.phone;
        this.event.wechat = res.data.details.wechat;
        this.event.partner = res.data.partner;
        this.event.product_list_id = res.data.product_list_id;
        this.event.order_id = res.data.order_id;
        var filethumbs = JSON.parse(res.data.details.images);
        this.event.files = [];
        for(var key in filethumbs) {
          if(this.shopImg(filethumbs[key])) {
            this.event.productlistimgs.push(filethumbs[key]);
          } else {
            this.event.filethumbs.push(filethumbs[key]);
            this.event.files.push(filethumbs[key].replace("_thumb", ""));
          }
        }
        if(this.event.files.length > 0) {
          this.hasFile = true;
        }
        this.event.apm = res.data.apm;
        if(res.data.apm == 5) {
          this.event.from.time = res.data.start_time;
          this.event.to.time = res.data.end_time;
        }
        this.event.from.floors = res.data.details.from_floor;
        this.event.from.elevator = res.data.details.from_elevator;
        this.event.to.floors = res.data.details.to_floor;
        this.event.to.elevator = res.data.details.to_elevator;
        this.event.comment = "";
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