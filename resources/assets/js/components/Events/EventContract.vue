<template>
  <div class="col-md-12 no-padding contract">
    <div class="logo pull-right"></div>
    <section class="content no-padding">
      <div class="box box-widget">
        <div class="box-header with-border no-padding">
          <div class="col-xs-12 controldiv">
            <label>
              <input type="radio" name="language" value="ja" v-model="language">
              <img src="/images/japan.png" width="26" />
            </label>
            <label>
              <input type="radio" name="language" value="cn" v-model="language">
              <img src="/images/china.png" width="26" />
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label>
              <input type="radio" name="pagetype" value="1" v-model="pagetype">
              見積書
            </label>
            <label>
              <input type="radio" name="pagetype" value="2" v-model="pagetype">
              請求書
            </label>
            <label>
              <input type="radio" name="pagetype" value="3" v-model="pagetype">
              契約書
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label>
              <input type="checkbox" v-model="shopoff20">
              8折券
            </label>
            <button class="btn btn-primary pull-right btn-xs" @click="close">返回</button>
          </div>
          <br /><br /><br />
          <div class="col-xs-12"><h3 class="text-center" @click="printmode = !printmode">{{ title }}</h3></div>
          <div class="col-xs-6">
            <h5>{{ $t('contract.workingtime') }}: {{ event.event_date }}</h5><br /><br />
            <h5>
              <span class="underline">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{ name }}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span>御中
            </h5>
          </div>
          <div class="col-xs-6">
            <div class="pull-right" v-if="pagetype == 1 || pagetype == 2">
              <span>{{ $t('contract.issuedate') }}: {{ today }}</span><br />
              <span class="datepicker">{{ $t('contract.expiration') }}: {{ expiration }}</span>
            </div>
          </div>
        </div>
        <div class="box-body">
          <div class="col-xs-12 no-padding">
            <div class="contractleft no-padding">
              <div class="col-xs-12 border">
                <div class="col-xs-12 no-padding" v-if="event.details && event.details.from_address">
                  <b>{{ $t('event.fromAddress') }}:</b> {{ event.details.from_address }}
                </div>
                <div class="col-xs-12 no-padding" v-if="event.details && event.details.to_address">
                  <b>{{ $t('event.toAddress') }}:</b> {{ event.details.to_address }} 
                </div>
                <div class="col-xs-12 no-padding" v-if="event.details.phone">
                  <b>{{ $t('contract.tel') }}:</b>
                  {{ event.details.phone }}
                </div>
              </div>
            </div>
            <div class="contractright border pull-right zylogopc">
              <img src="/images/yinz.png" class="seal" />
              子義引越センター<br />
              子義合同会社<br />
              東京都足立区入谷9丁目28−19<br />
              03-5856-8281<br />
              担当:イトウ
            </div>
            <div class="contractright border pull-right zylogom">
              子義引越センター<br />
              子義合同会社<br />
              東京都足立区入谷9丁目28−19<br />
              03-5856-8281<br />
              担当:イトウ
            </div>
          </div>
          <div class="col-xs-12 border details">
            <div class="col-xs-6 no-padding" v-if="event.details.from_btype > 0 || event.details.from_elevator > 0 || event.details.from_floor > 0">
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
            <div class="col-xs-6 no-padding" v-if="event.details.to_btype > 0 || event.details.to_elevator > 0 || event.details.to_floor > 0">
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
            <div class="col-xs-12 no-padding amount" v-if="event.amount">
              <h5 v-if="pagetype == 2">
                下记の通り、<span class="underline">{{ byname }}</span>ご請求申し上げます。
              </h5>
              <h5>
                <span v-if="event.amount" class="marginr3"><b>{{ amounttext }}{{ event.amount | formatNumberJPY }}{{ $t('contract.taxincluded') }}</b></span>
              </h5>
              <div v-if="pagetype != 2">
                <hr />
                <h5><u>{{ $t('contract.comments') }}:</u></h5>
                <h5 v-for="comment in event.comments">{{ comment.content }}</h5>
                <hr />
              </div>
            </div>
            <div class="col-xs-8 no-padding" v-if="pagetype == 1">
              <b>子義优势:</b><br />
              ❤{{ $t('contract.explanation1') }}<br />
              ❤{{ $t('contract.explanation2') }}<br />
              ❤{{ $t('contract.explanation3') }}<br />
              ❤{{ $t('contract.explanation4') }}<br />
              ❤{{ $t('contract.explanation5') }}<br />
              ❤{{ $t('contract.explanation6') }}<br />
              ❤{{ $t('contract.explanation7') }}<br />
              ❤{{ $t('contract.explanation8') }}
            </div>
            <div class="col-xs-8 no-padding" v-if="pagetype == 3">
              <div class="col-xs-6 no-padding">
                <b>事前必读:</b><br />
                ⭐团地入口🔑钥匙一定要提前拿到 <br />
                ⭐新家钥匙🔑一定随身携带 <br />
                ⭐空调遥控器务必放在外面  不要打包<br />
                ⭐冰箱提前3小时断电 里面物品务必清空 <br />
                ⭐有管理员的建筑最好提前跟管理员打招呼 <br />
                ⭐预约完成后搬家日前一周以内不能变更日期 希望您能谅解！<br /> 
                ⭐我们工作人员到之前一定要打包结束 散东西务必装箱！ 箱子务必封口！否则视耽误时间长短追加料金 <br />
                ⭐我们双方都有法律义务保证契约内容不要有变化   <br />
                ⭐无论因为什么原因必须要取消  我们都可以表示理解   但是您要支付50%搬家费作为违约金   希望您能谅解(一周内取消80%  一天前取消100%)<br />
                ⭐由于客人原因造成作业员必须要等30分钟以上开始计费(每人5000/小时)<br />
                感谢您的合作 我们将尽力给您提供最贴心省心放心的服务！
              </div>
              <div class="col-xs-6">
                <b>子義优势:</b><br />
                ❤{{ $t('contract.explanation1') }}<br />
                ❤{{ $t('contract.explanation2') }}<br />
                ❤{{ $t('contract.explanation3') }}<br />
                ❤{{ $t('contract.explanation4') }}<br />
                ❤{{ $t('contract.explanation5') }}<br />
                ❤{{ $t('contract.explanation6') }}<br />
                ❤{{ $t('contract.explanation7') }}<br />
                ❤{{ $t('contract.explanation8') }}
              </div>
            </div>
            <div class="col-xs-4 no-padding" v-if="shopoff20">
              <img src="/images/shop20off240.png" width="240" />
            </div>
          </div>
          <div class="col-xs-12 no-padding details">
            <div class="col-xs-12 no-padding">
              {{ $t('contract.commission') }}
            </div>
            <div class="col-xs-6 no-padding">
              <b>{{ $t('contract.payee2') }}</b><br />
              三菱UFJ銀行<br />
              支店名:王子駅前支店　店番:763<br />
              口座番号:0239149<br />
              名義:コヨシ ゴウドウカイシヤ　普通預金
            </div>
          </div>
          <div class="col-xs-6 no-padding pull-right" v-if="pagetype == 3">
            <b>署名(サイン):_________________</b>
          </div>
        </div>
      </div>
      <div class="inputs col-xs-6 form-horizontal controldiv">
        <div class="form-group">
          <label for="inputname" class="col-sm-2 control-label">客户名称</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="inputname" v-model="name">
          </div>
        </div>
      </div>
      <div class="inputs col-xs-6 form-horizontal controldiv" v-show="pagetype == 2">
        <div class="form-group">
          <label for="byname" class="col-sm-2 control-label">请求名义</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="byname" v-model="byname">
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
      language: "ja",
      pagetype: 1,
      title: this.$t('contract.quotation'),
      amounttext: this.$t('contract.amount'),
      hasFile: false,
      showImgs: false,
      printmode: false,
      shopoff20: false,
      today: moment().format('YYYY年MM月DD日'),
      expiration: moment().add(7, 'd').format('YYYY年MM月DD日'),
      event: {
        details: {}
      },
      name: "",
      byname: "",
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
    updatepagetype() {
      if(this.pagetype == 1){
        this.title = this.$t('contract.quotation');
        this.amounttext = this.$t('contract.amount');
      } else if(this.pagetype == 2) {
        this.title = this.$t('contract.invoice');
        this.amounttext = this.$t('contract.invoiceamount');
      } else {
        this.title = this.$t('contract.contract');
        this.amounttext = this.$t('contract.amount');
      }
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
      this.updatepagetype();
    },
    printmode(isprint) {
      if(isprint) {
        $('.controldiv').hide();
        $('.main-sidebar').hide();
        $('.content-wrapper').removeClass('content-wrapper').addClass('mleft0');
        $('.wrapper').css('background-color', '#FFF');
      } else {
        $('.controldiv').show();
        $('.main-sidebar').show();
        $('.mleft0').removeClass('mleft0').addClass('content-wrapper');
        $('.wrapper').css('background-color', '#222d32');
      }
    },
    pagetype(num) {
      if(num == 1){
        this.title = this.$t('contract.quotation');
        this.amounttext = this.$t('contract.amount');
      } else if(num == 2) {
        this.title = this.$t('contract.invoice');
        this.amounttext = this.$t('contract.invoiceamount');
      } else {
        this.title = this.$t('contract.contract');
        this.amounttext = this.$t('contract.amount');
      }
    }
  }
}
</script>

<style>
.contract {
  font-size: 12px;
}
.details {
  margin-bottom: 25px!important;
  margin-top: 5px;
}
.contractleft {
  width: 55%;
  height: 130px;
  display: inline-grid;
}
.contractright {
  width: 40%;
  height: 130px;
}
.col-xs-4 {
  padding:0 10px;
}
.inputs {
  margin-top: 150px;
}
.mleft0 {
  margin-left: 0!important;
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