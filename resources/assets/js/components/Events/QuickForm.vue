<template>
  <div>
    <div class="box box-primary">
      <div class="box-header">
        <a id="boxtitle"></a>
        <h3 class="box-title">
          {{ boxtitle }} 
          <small v-if="eventid">#{{ eventid }}</small>
        </h3>
        <div v-if="!eventid" class="pull-right">
          <button type="button" class="btn btn-primary btn-xs" @click="expenditureEvent = !expenditureEvent">
            <span v-show="expenditureEvent">{{ $t('event.pagetitle') }}</span>
            <span v-show="!expenditureEvent">{{ $t('event.expenditureEvent') }}</span>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div class="col-xs-12 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-tags"></i></span>
            <div class="checkedgroup" @click="showType(1)">
              <template v-if="selected.worktype.length > 0">
                <span class="label label-success" v-for="(type, key) in selected.worktype"><i class="fa fa-tag"></i> {{ type }}</span>
              </template>
              <template v-else>
                <span class="inputplaceholder">{{ $t('event.workType') }}</span>
              </template>
            </div>
          </div>
        </div>
        <div class="col-xs-12 no-padding" v-show="expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-tags"></i></span>
            <div class="checkedgroup" @click="showType(6)">
              <template v-if="selected.worktype.length > 0">
                <span class="label label-success" v-for="(type, key) in selected.worktype"><i class="fa fa-tag"></i> {{ type }}</span>
              </template>
              <template v-else>
                <span class="inputplaceholder">{{ $t('event.typeofexpenditure') }}</span>
              </template>
            </div>
          </div>
        </div>
        <div class="col-xs-12 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-cube"></i></span>
            <div class="checkedgroup" @click="showType(2)">
              <template v-if="selected.aboutgoods.length > 0">
                <span class="label label-primary" v-for="(type, key) in selected.aboutgoods"><i class="fa fa-cube"></i> {{ type }}</span>
              </template>
              <template v-else>
                <span class="inputplaceholder">{{ $t('event.aboutgoods') }}</span>
              </template>
            </div>
          </div>
        </div>
        <div class="col-xs-12 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-exclamation-triangle"></i></span>
            <div class="checkedgroup" @click="showType(3)">
              <template v-if="selected.careful.length > 0">
                <span class="label label-danger" v-for="(type, key) in selected.careful"><i class="fa fa-exclamation-triangle"></i> {{ type }}</span>
              </template>
              <template v-else>
                <span class="inputplaceholder">{{ $t('event.typeofcareful') }}</span>
              </template>
            </div>
          </div>
        </div>
        <div class="col-xs-12 no-padding"> 
          <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
              <div class="checkedgroup" @click="showType(4)">
                <template v-if="selected.total.length > 0">
                  <span class="label label-primary" v-for="(type, key) in selected.total"><i class="fa fa-cubes"></i> {{ type }}</span>
                </template>
                <template v-else>
                  <span class="inputplaceholder">{{ $t('event.typeoftotal') }}</span>
                </template>
              </div>
            </div>
          </div>
          <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-truck"></i></span>
              <div class="checkedgroup" @click="showType(5)">
                <template v-if="selected.truck.length > 0">
                  <span class="label label-primary" v-for="(type, key) in selected.truck"><i class="fa fa-truck"></i> {{ type }}</span>
                </template>
                <template v-else>
                  <span class="inputplaceholder">{{ $t('event.typeoftruck') }}</span>
                </template>
              </div>
            </div>
          </div>
        </div> 
        <!-- datepicker --> 
        <div class="col-xs-6 no-padding">
          <div class="input-group"> 
            <div class="input-group-addon"> 
              <i class="fa fa-calendar"></i> 
            </div> 
            <input type="text" id="eventdate" name="eventdate" class="form-control datepicker" v-model="event.eventdate" :placeholder="$t('event.eventdate')" readonly /> 
          </div>
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group"> 
            <div class="radio padding-left3">
              <label>
                <input type="radio" name="apm" value="1" v-model="event.apm">
                {{ $t('event.morning') }} 
              </label>
              <label>
                <input type="radio" name="apm" value="2" v-model="event.apm">
                {{ $t('event.afternoon') }} 
              </label>
              <label>
                <input type="radio" name="apm" value="3" v-model="event.apm">
                {{ $t('event.night') }} 
              </label>
              <label>
                <input type="radio" name="apm" value="4" v-model="event.apm">
                {{ $t('event.allday') }} 
              </label>
            </div>
          </div> 
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group"> 
            <div class="input-group-addon"> 
              <input type="radio" name="apm" value="5" v-model="event.apm" /> 
            </div> 
            <input type="text" id="stime" class="form-control datepickertime" disabled v-model="event.from.time" /> 
            <div class="input-group-addon"> 
              〜
            </div> 
            <input type="text" id="ttime" class="form-control datepickertime" disabled v-model="event.to.time" /> 
          </div> 
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-male"></i></span>
            <input type="text" class="form-control" name="partner" v-model="event.partner" :placeholder="$t('event.partner')">
          </div>
        </div>
        <div class="col-xs-6 no-padding">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-jpy"></i></span>
            <input type="number" class="form-control" name="amount" v-model.number="event.amount" :placeholder="$t('event.amount')" v-show="!expenditureEvent">
            <input type="number" class="form-control" name="amount" v-model.number="event.amount" :placeholder="$t('event.expenditureEvent')+$t('finance.amount')" v-show="expenditureEvent">
          </div>
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
            <select2 :options="options.product_list" :change="true" @change="getProductlist" id="product_list_id" :selected="event.product_list_id" :placeholder="$t('event.shoppingListId')" :multiple="false"></select2>
          </div>
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
            <input type="text" class="form-control" name="phone" v-model="event.phone" :placeholder="$t('event.phoneNumber')">
          </div>
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-wechat"></i></span>
            <input type="text" class="form-control" name="wechat" v-model="event.wechat" :placeholder="$t('event.wechat')">
          </div>
        </div>
        <div class="col-xs-6 no-padding" v-show="!expenditureEvent">
          <div class="input-group">
            <span class="input-group-addon deposit">{{ $t('event.depositjpy') }}</span>
            <input type="number" class="form-control" v-model.number="event.deposit_jpy">
            <span class="input-group-addon" @click="event.deposit_jpy += 10000"><i class="fa fa-plus blue"></i></span>
            <span class="input-group-addon" @click="event.deposit_jpy > 10000 ? event.deposit_jpy -= 10000 : event.deposit_jpy = 0"><i class="fa fa-minus blue"></i></span>
        </div>
        </div>
        <div class="col-xs-6 no-padding">
          <div class="input-group" v-show="!expenditureEvent">
            <span class="input-group-addon deposit">{{ $t('event.depositrmb') }}</span>
            <input type="number" class="form-control" v-model.number="event.deposit_rmb">
            <span class="input-group-addon" @click="event.deposit_rmb += 100"><i class="fa fa-plus blue"></i></span>
            <span class="input-group-addon" @click="event.deposit_rmb > 100 ? event.deposit_rmb -= 100 : event.deposit_rmb = 0"><i class="fa fa-minus blue"></i></span>
          </div>
        </div>
        <div v-show="event.deposit_jpy > 0">
          <div class="col-xs-6 no-padding">
            <div class="input-group">
              <span class="input-group-addon deposit">{{ $t('event.receipt') }}</span>
              <input type="text" id="receipted_at" name="receipted_at" class="form-control datepicker" v-model="event.receipted_at" :placeholder="$t('event.receipted_at')" readonly />
            </div>
          </div>
          <div class="col-xs-6 no-padding">
            <div class="input-group"> 
              <div class="radio padding-left3">
                <label>
                  <input type="radio" name="deposit_type" value="1" v-model="event.deposit_type">
                  {{ $t('event.payment1') }} 
                </label>
                <label>
                  <input type="radio" name="deposit_type" value="2" v-model="event.deposit_type">
                  {{ $t('event.payment2') }} 
                </label>
                <label>
                  <input type="radio" name="deposit_type" value="3" v-model="event.deposit_type">
                  {{ $t('event.payment3') }} 
                </label>
              </div>
            </div> 
          </div>
        </div>
        <div class="input-group" v-show="!expenditureEvent">
          <span class="input-group-addon"><i class="fa fa-sign-out"></i></span>
          <input type="text" class="form-control" name="fromadd" v-model="event.from.address" :placeholder="$t('event.fromAddress')">
          <span class="input-group-btn">
            <button type="button" class="btn bg-olive btn-flat" @click="showModal = true">{{ setDetailsFrom }}</button>
          </span>
        </div>
        <div class="input-group" v-show="!expenditureEvent">
          <span class="input-group-addon"><i class="fa fa-sign-in"></i></span>
          <input type="text" class="form-control" name="toadd" v-model="event.to.address" :placeholder="$t('event.toAddress')">
          <span class="input-group-btn">
            <button type="button" class="btn bg-olive btn-flat" @click="showModal = true">{{ setDetailsTo }}</button>
          </span>
        </div>
        <div class="form-group">
          <textarea class="form-control" rows="4" name="comment" v-model="event.comment" :placeholder="$t('event.comment')"></textarea>
        </div>
        <div v-show="hasFile" class='col-xs-12 no-padding'>
          <div class="gallery">
            <div v-for="(file, key) in event.files" class="col-xs-2 no-padding marginb8">
              <a :href="'/uploads/' + file" class="thumbnail" :title="file | truncate(25)">
                <img :src="'/uploads/' + event.filethumbs[key]" :alt="file">
              </a>
              <div class='text-right'>
                <button type="button" class="btn btn-xs btn-default" @click="removeFile(event.filethumbs[key])">{{ $t('global.remove') }}</button>
              </div>
            </div>
            <div v-for="(img, key) in event.productlistimgs" class="col-xs-2 no-padding marginb8">
              <a :href="img" class="thumbnail" :title="img | truncate(25)">
                <img :src="img" :alt="img">
              </a>
              <div class='text-right'>
                <button type="button" class="btn btn-xs btn-default" @click="removeFile(event.productlistimgs[key], 2)">{{ $t('global.remove') }}</button>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <upload-file :fileAccept="fileAccept" @update-file="updateFiles" @update-loading="setLoading" @error-push="errorPush"></upload-file>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <div v-if="errors.length">
          <b>{{ $t('event.PleaseCorrectTheFollowingError') }}:</b>
          <ul>
            <li v-for="error in errors">{{ error }}</li>
          </ul>
        </div>
        <button type="button" class="btn btn-default" @click="$emit('closeform')">{{ $t('global.Cancel') }}</button>
        <button @click="checkForm" type="button" class="btn btn-primary pull-right">{{ $t('global.Submit') }}</button> 
      </div>
    </div>
    
    <modal v-if="showConfirmModal" @close="showConfirmModal = false" @sure="setSure">
    </modal>
    <modal v-if="showModal" @close="showModal = false">
      <h4 slot="header">{{ $t('event.setDetails') }}</h4>
      <div slot="body">
        <div class="form-group">
          <label>{{ $t('event.fromAddress') }}</label>
          <label :class="{ checked: event.from.elevator == 1 }">
            <input type="radio" name="fromelevator" value="1" v-model="event.from.elevator">
            {{ $t('event.elevator') }}
          </label>
          <label :class="{ checked: event.from.elevator == 2 }">
            <input type="radio" name="fromelevator" value="2" v-model="event.from.elevator">
            {{ $t('event.stairs') }}
          </label>
          <label :class="{ checked: event.from.elevator == 3 }">
            <input type="radio" name="fromelevator" value="3" v-model="event.from.elevator">
            {{ $t('event.elevatorAndstairs') }}
          </label>
          <label>{{ $t('event.floors') }}</label>
          <select name="fromfloors" v-model="event.from.floors">
            <option value="0">{{ $t('global.unset') }}</option>
            <option v-for="floor of floors" :value="floor"><div v-if="floor > 9">{{ floor }}+</div><div v-else>{{ floor }}</div></option>
          </select>
          <select name="frombtype" v-model="event.from.btype">
            <option v-for="(btype, key) of buildingtypes" :value="key">{{ btype }}</option>
          </select>
        </div>
        <div class="form-group">
          <label>{{ $t('event.toAddress') }}</label>
          <label :class="{ checked: event.to.elevator == 1 }">
            <input type="radio" name="toelevator" value="1" v-model="event.to.elevator">
            {{ $t('event.elevator') }}
          </label>
          <label :class="{ checked: event.to.elevator == 2 }">
            <input type="radio" name="toelevator" value="2" v-model="event.to.elevator">
            {{ $t('event.stairs') }}
          </label>
          <label :class="{ checked: event.to.elevator == 3 }">
            <input type="radio" name="toelevator" value="3" v-model="event.to.elevator">
            {{ $t('event.elevatorAndstairs') }}
          </label>
          <label>{{ $t('event.floors') }}</label>
          <select name="tofloors" v-model="event.to.floors">
            <option value="0">{{ $t('global.unset') }}</option>
            <option v-for="floor of floors" :value="floor"><div v-if="floor > 9">{{ floor }}+</div><div v-else>{{ floor }}</div></option>
          </select>
          <select name="tobtype" v-model="event.to.btype">
            <option v-for="(btype, key) of buildingtypes" :value="key">{{ btype }}</option>
          </select>
        </div>
      </div>
      <div slot="footer">
        <button class="btn btn-xs btn-default" @click="resetDetails">
          Reset
        </button>
        <button class="btn btn-xs btn-primary pull-right" @click="showModal = false">
          OK
        </button>
      </div>
    </modal>
    <modal v-if="showTypeModal" @close="showTypeModal = false">
      <h4 slot="header">{{ typeboxtitle }}<button type="button" class="close" @click="showTypeModal = false"><span>×</span></button></h4>
      <div slot="body">
        <label class="checkbox-inline" v-for="(type, key) in clicktypes">
          <template v-if="showingType == 1">
            <input type="checkbox" v-model="clickedworktype" :value="type"> {{ type.text }}
          </template>
          <template v-else-if="showingType == 2">
            <input type="checkbox" v-model="clickedaboutgoods" :value="type"> {{ type.text }}
          </template>
          <template v-else-if="showingType == 3">
            <input type="checkbox" v-model="clickedcareful" :value="type"> {{ type.text }}
          </template>
          <template v-else-if="showingType == 4">
            <input type="checkbox" v-model="clickedtotal" :value="type"> {{ type.text }}
          </template>
          <template v-else-if="showingType == 5">
            <input type="checkbox" v-model="clickedtruck" :value="type"> {{ type.text }}
          </template>
          <template v-else-if="showingType == 6">
            <input type="checkbox" v-model="clickedexpenditure" :value="type"> {{ type.text }}
          </template>
        </label>
      </div>
      <div slot="footer">
        <button class="btn btn-xs btn-primary pull-right" @click="showTypeModal = false">
          Ok
        </button>
      </div>
    </modal>
    <script type="text/x-template" id="modal-template">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">

              <div class="modal-header">
                <slot name="header">
                  {{ $t('global.warning') }}
                </slot>
              </div>

              <div class="modal-body">
                <slot name="body">
                  {{ $t('event.noPhoneNumberAndWechat') }}{{ $t('global.areYouSure') }}
                </slot>
              </div>

              <div class="modal-footer text-left">
                <slot name="footer">
                  <button class="btn btn-xs btn-default" @click="$emit('close')">
                    {{ $t('global.Cancel') }}
                  </button>
                  <button class="btn btn-xs btn-primary pull-right" @click="setSure">
                    {{ $t('global.Submit') }}
                  </button>
                </slot>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </script>
    <loading :loadingShow="loadingShow"></loading>
  </div>
</template>

<script>
import Vue from 'vue'
import Select2 from './Select2'
import Loading from '../Public/Loading'
import UploadFile from '../Public/UploadFile'
import moment from 'moment'
import datetimepicker from 'jquery-datetimepicker'
import baguetteBox from 'baguettebox.js'

export default {
  props: ['eventdate', 'eventid', 'copyid', 'formoptions', 'productlistid', 'estimatedate'],
  mounted() {
    var self = this;
    this.setDatePicker()
    this.options = this.formoptions;
    
    if(self.eventid) {
      this.getEvent(self.eventid)
    } else if(self.copyid) {
      this.getEvent(self.copyid, true)
      this.boxtitle += 'コピー'
    } else if(self.eventdate) {
      this.event.eventdate = self.eventdate
    } else if(self.productlistid) {
      this.setPerductListId(self.productlistid)
    } else if(self.estimatedate) {
      this.setEstimate(self.estimatedate)
    }
  },
  components: {
    Select2,
    UploadFile,
    Loading
  },
  data() {
    return {
      options: [],
      value: [],
      placeholder: "",
      errors: [],
      showModal: false,
      showConfirmModal: false,
      showTypeModal: false,
      loadingShow: false,
      expenditureEvent: false,
      warning: {
        message: "",
        action: "",
        sure: true
      },
      floors: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '20', '30', '40', '50'],
      setDetailsFrom: this.$t('event.setDetails'),
      setDetailsTo: this.$t('event.setDetails'),
      hasFile: false,
      fileAccept: "image/*",
      boxtitle: this.eventid ? this.$t('global.edit') : this.$t('global.add') + this.$t('event.pagetitle'),
      typeboxtitle: "",
      clicktypes: [],
      showingType: 0,
      buildingtypes: [
        this.$t('offer.buildingtype'), 
        this.$t('offer.buildingtype1'), 
        this.$t('offer.buildingtype2'), 
        this.$t('offer.buildingtype3'), 
        this.$t('offer.buildingtype4'), 
        this.$t('offer.buildingtype5'), 
        this.$t('offer.buildingtype6')
      ],
      event: {
        id: "",
        eventdate: "",
        apm: "",
        status: 1,
        from: {
          time: "",
          address: "",
          elevator: "",
          floors: "0",
          btype: "0"
        },
        to: {
          time: "",
          address: "",
          elevator: "",
          floors: "0",
          btype: "0"
        },
        worktype: [],
        aboutgoods: [],
        careful: [],
        total: [],
        truck: [],
        expenditure: [],
        wechat: "",
        phone: "",
        partner: null,
        amount: null,
        product_list_id: "",
        order_id: "",
        files: [],
        filethumbs: [],
        productlistimgs: [],
        deposit_jpy: 0,
        deposit_rmb: 0,
        deposit_type: 0,
        receipted_at: "",
        comment: ""
      },
      selected: {
        worktype: [],
        aboutgoods: [],
        careful: [],
        total: [],
        truck: [],
        expenditure: []
      },
      clickedworktype: [],
      clickedaboutgoods: [],
      clickedcareful: [],
      clickedtotal: [],
      clickedtruck: [],
      clickedexpenditure: []
    }
  },
  watch: {
    eventapm: function(val) {
      if(val == 5) {
        $('.datepickertime').attr('disabled', false);
      } else {
        $('.datepickertime').attr('disabled', true);
        this.event.from.time = "";
        this.event.to.time = "";
      }
    },
    clickedworktype: function(val) {
      var listvalue = [];
      var listtext = [];
      for(var i in val) {
        listvalue.push(val[i].id.toString());
        listtext.push(val[i].text);
      }
      this.setType(listvalue, listtext);
    },
    clickedaboutgoods: function(val) {
      if(this.showingType) {
        var listvalue = [];
        var listtext = [];
        for(var i in val) {
          listvalue.push(val[i].id.toString());
          listtext.push(val[i].text);
        }
        this.setType(listvalue, listtext);
      }
    },
    clickedtotal: function(val) {
      var listvalue = [];
      var listtext = [];
      for(var i in val) {
        listvalue.push(val[i].id.toString());
        listtext.push(val[i].text);
      }
      this.setType(listvalue, listtext);
    },
    clickedcareful: function(val) {
      var listvalue = [];
      var listtext = [];
      for(var i in val) {
        listvalue.push(val[i].id.toString());
        listtext.push(val[i].text);
      }
      this.setType(listvalue, listtext);
    },
    clickedtruck: function(val) {
      var listvalue = [];
      var listtext = [];
      for(var i in val) {
        listvalue.push(val[i].id.toString());
        listtext.push(val[i].text);
      }
      this.setType(listvalue, listtext);
    },
    clickedexpenditure: function(val) {
      var listvalue = [];
      var listtext = [];
      for(var i in val) {
        listvalue.push(val[i].id.toString());
        listtext.push(val[i].text);
      }
      this.setType(listvalue, listtext);
    },
    expenditureEvent(bool) {
      if (!this.event.id) {
        this.event.worktype = this.selected.worktype = this.clickedworktype = this.clickedexpenditure = [];
        if (bool) {
          // 支出日程
          this.boxtitle = this.$t('global.add') + this.$t('event.expenditureEvent');
        } else {
          // 工作计划
          this.boxtitle = this.$t('global.add') + this.$t('event.pagetitle');
        }
      }
    }
  },
  computed: {
    eventapm: function() {
      return this.event.apm;
    }
  },
  updated: function () {
    this.$nextTick(function () {
      if(this.event.files.length > 0 || this.event.productlistimgs.length > 0) {
        baguetteBox.run('.gallery', {
          noScrollbars: true
        });
      }
      // if(this.productlistid) {
      //   $('#product_list_id').val(this.productlistid).trigger('change');
      //   self.productlistid = "";
      // }
    })
  },
  methods: {
    setDatePicker() {
      const self = this
 
      $.datetimepicker.setLocale('zh')
      $('.datepicker').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('.datepickertime').datetimepicker({datepicker:false, format:'H:i'})
      $('#eventdate').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.eventdate = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.event.eventdate = '';
        }
      })
      $('#receipted_at').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.receipted_at = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.event.receipted_at = '';
        }
      })
      $('#ttime').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.to.time = $(e.target).val()
        } else {
          self.event.to.time = '';
        }
      })
      $('#stime').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.from.time = $(e.target).val()
        } else {
          self.event.from.time = '';
        }
      })
    },
    checkForm:function(e) {
      this.errors = [];
      if(!this.event.eventdate){
        this.errors.push(this.$t('event.eventdate') + this.$t('global.required'));
        return;
      }
      if(this.event.worktype.length == 0) {
        this.errors.push(this.$t('event.workType') + this.$t('global.required'));
        return;
      }
      if (this.event.deposit_jpy > 0 && (this.event.receipted_at == "" || this.event.deposit_type == 0)) {
        // 有定金时则必须输入收款日期和类型
        this.errors.push(this.$t('event.receipted_at_and_type') + this.$t('global.required'));
        return;
      }
      if (!this.expenditureEvent) {
        if(!this.event.apm){
          this.errors.push(this.$t('event.apmAllDayOrTime') + this.$t('global.moreThanOne'));
          return;
        }
        if(this.event.apm == 5 && !this.event.from.time && !this.event.to.time){
          this.errors.push(this.$t('event.eventtimeChecked') + this.$t('event.eventtime') + this.$t('global.required'));
          return;
        }
        if(this.event.from.time && this.event.to.time && moment(this.event.eventdate+' '+this.event.from.time) > moment(this.event.eventdate+' '+this.event.to.time)) {
          this.errors.push(this.$t('event.timereverse'));
          return;
        }
        if(!this.event.wechat && !this.event.phone) {
          this.warning.sure = false;
          this.showConfirmModal = true;
          return;
        } else {
          this.warning.sure = true;
        }
      } else {
        //支出
        if(!this.event.amount){
          this.errors.push(this.$t('event.expenditureEvent') + this.$t('global.required'));
          return;
        }
        // 默认时间为全天
        this.event.apm = 4;
        this.event.status = 3;
      }
      
      if(this.warning.sure) {
        this.submitForm();
      }
    },
    submitForm() {
      // this.event.worktype = $('#worktype').val();
      // this.event.aboutgoods = $('#aboutgoods').val();
      // this.event.careful = $('#careful').val();
      // this.event.total = $('#total').val();
      // this.event.truck = $('#truck').val();
      this.event.product_list_id = $('#product_list_id').val();
      this.loadingShow = true;
      if(this.event.id) {
        //更新
        this.$http.put('/admin/event/' + this.event.id, this.event).then(response => {
          var ymd = response.data.event_date;
          this.$emit('addedevent', ymd);
          this.loadingShow = false;
        }).catch(error => {
          this.FatalError(error.response);
          this.errors.push(this.$t('global.calltheadministrator'));
          this.loadingShow = false;
        });
      } else {
        //新建
        this.$http.post('/admin/event', this.event).then(response => {
          var ymd = response.data.event_date;
          this.$emit('addedevent', ymd);
          this.loadingShow = false;
        }).catch(error => {
          this.FatalError(error.response);
          this.errors.push(this.$t('global.calltheadministrator'));
          this.loadingShow = false;
        });
      }
    },
    resetDetails() {
      var message = this.$t('global.areYouSure') + this.$t('event.resetDetails');
      if(window.confirm(message)) {
        this.event.from.elevator = "";
        this.event.to.elevator = "";
        this.event.from.floors = "0";
        this.event.to.floors = "0";
        this.event.from.btype = "0";
        this.event.to.btype = "0";
      }
    },
    setType(listvalue, listtext) {
      switch (this.showingType) {
        case 1:
          this.event.worktype = listvalue;
          this.selected.worktype = listtext;
          break;
        case 2:
          this.event.aboutgoods = listvalue;
          this.selected.aboutgoods = listtext;
          break;
        case 3:
          this.event.careful = listvalue;
          this.selected.careful = listtext;
          break;
        case 4:
          this.event.total = listvalue;
          this.selected.total = listtext;
          break;
        case 5:
          this.event.truck = listvalue;
          this.selected.truck = listtext;
          break;
        case 6:
          this.event.worktype = listvalue;
          this.selected.worktype = listtext;
          break;
      }
    },
    setSure() {
      this.showConfirmModal = false;
      this.submitForm();
    },
    setLoading(switchTF) {
      this.loadingShow = switchTF;
    },
    updateFiles(file) {
      for(var i in file) {
        var bigfile = file[i].replace("_thumb", "");
        this.event.files.push(bigfile);
        this.event.filethumbs.push(file[i]);
      }
      if(this.event.files.length > 0) {
        this.hasFile = true;
      } else {
        this.hasFile = false;
      }
    },
    removeFile(file, uploaded = 1) {
      if(window.confirm('Are you sure remove this photo?' + file)) {
        if(uploaded == 1) {
          var bigfile = file.replace("_thumb", "");
          var index = this.event.files.indexOf(bigfile);
          this.event.files.splice(index, 1);
          this.event.filethumbs.splice(index, 1);
        } else {
          var index = this.event.productlistimgs.indexOf(file);
          this.event.productlistimgs.splice(index, 1);
        }
      }
    },
    showType(type) {
      // 1 worktype 2 aboutgoods 3 careful 4 total 5 truck 6 expenditure
      this.showingType = type;
      switch (type) {
        case 1:
          this.typeboxtitle = this.$i18n.t('event.workType');
          this.clicktypes = this.options.worktype;
          break;
        case 2:
          this.typeboxtitle = this.$i18n.t('event.aboutgoods');
          this.clicktypes = this.options.aboutgoods;
          break;
        case 3:
          this.typeboxtitle = this.$i18n.t('event.typeofcareful');
          this.clicktypes = this.options.careful;
          break;
        case 4:
          this.typeboxtitle = this.$i18n.t('event.typeoftotal');
          this.clicktypes = this.options.total;
          break;
        case 5:
          this.typeboxtitle = this.$i18n.t('event.typeoftruck');
          this.clicktypes = this.options.truck;
          break;
        case 6:
          this.typeboxtitle = this.$i18n.t('event.typeofexpenditure');
          this.clicktypes = this.options.expenditure;
          break;
      }
      this.showTypeModal = true;
    },
    errorPush(message) {
      this.errors.push(message);
    },
    getProductlist(listid){
      if(listid) {
        this.event.comment = "";
        this.event.productlistimgs = [];
        this.$http({
          url: '/admin/productlist/' + listid,
          method: 'GET'
        }).then(res =>  {
          if(res.data.id) {
            if(res.data.note && !this.event.id) {
              this.event.comment = res.data.note;
            }
            for(var key in res.data.items) {
              if(res.data.items[key].image) {
                this.event.productlistimgs.push(res.data.items[key].image);
              }
            }
            if(this.event.productlistimgs.length > 0) {
              this.hasFile = true;
            }
          } else {
            console.log("no value");
          }
        })
      }
    },
    getEvent(eventid, iscopy = false) {
      this.$http({
        url: '/admin/event/' + eventid,
        method: 'GET'
      }).then(res =>  {
        this.event.status = res.data.status;
        var wktype = this.options.worktype;
        if (this.event.status == 3) {
          //支出任务
          this.expenditureEvent = true;
          wktype = this.options.expenditure;
        }
        if(!iscopy) {
          this.event.id = eventid;
          this.event.worktype = JSON.parse(res.data.types);
          var listtext = [];
          for(var i in this.event.worktype) {
            for(var key in wktype) {
              if(wktype[key].id == this.event.worktype[i]) {
                var type = new Object();
                type.id = this.event.worktype[i];
                type.text = wktype[key].text;
                this.clickedworktype.push(type);
                listtext.push(wktype[key].text);
              }
            }
          }
          this.selected.worktype = listtext;
        }
        this.event.aboutgoods = JSON.parse(res.data.details.aboutgoods);
        var listtext = [];
        for(var i in this.event.aboutgoods) {
          for(var key in this.options.aboutgoods) {
            if(this.options.aboutgoods[key].id == this.event.aboutgoods[i]) {
              var type = new Object();
              type.id = this.event.aboutgoods[i];
              type.text = this.options.aboutgoods[key].text;
              this.clickedaboutgoods.push(type);
              listtext.push(this.options.aboutgoods[key].text);
            }
          }
        }
        this.selected.aboutgoods = listtext;
        this.event.careful = JSON.parse(res.data.details.carefully);
        var listtext = [];
        for(var i in this.event.careful) {
          for(var key in this.options.careful) {
            if(this.options.careful[key].id == this.event.careful[i]) {
              var type = new Object();
              type.id = this.event.careful[i];
              type.text = this.options.careful[key].text;
              this.clickedcareful.push(type);
              listtext.push(this.options.careful[key].text);
            }
          }
        }
        this.selected.careful = listtext;
        this.event.truck = JSON.parse(res.data.details.trucks);
        var listtext = [];
        for(var i in this.event.truck) {
          for(var key in this.options.truck) {
            if(this.options.truck[key].id == this.event.truck[i]) {
              var type = new Object();
              type.id = this.event.truck[i];
              type.text = this.options.truck[key].text;
              this.clickedtruck.push(type);
              listtext.push(this.options.truck[key].text);
            }
          }
        }
        this.selected.truck = listtext;
        this.event.total = JSON.parse(res.data.total);
        var listtext = [];
        for(var i in this.event.total) {
          for(var key in this.options.total) {
            if(this.options.total[key].id == this.event.total[i]) {
              var type = new Object();
              type.id = this.event.total[i];
              type.text = this.options.total[key].text;
              this.clickedtotal.push(type);
              listtext.push(this.options.total[key].text);
            }
          }
        }
        this.selected.total = listtext;
        this.event.amount = res.data.amount;
        this.event.eventdate = res.data.event_date;
        this.event.from.address = res.data.details.from_address;
        this.event.to.address = res.data.details.to_address;
        this.event.phone = res.data.details.phone;
        this.event.wechat = res.data.details.wechat;
        this.event.partner = res.data.partner;
        this.event.product_list_id = res.data.product_list_id;
        this.event.order_id = res.data.order_id;
        if(res.data.deposit) {
          this.event.deposit_rmb = parseInt(res.data.deposit.rmb);
          this.event.deposit_jpy = parseInt(res.data.deposit.jpy);
          this.event.receipted_at = res.data.deposit.receipted_at;
          this.event.deposit_type = res.data.deposit.type;
        }
        var filethumbs = JSON.parse(res.data.details.images);
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
        if(res.data.details.from_btype) {
          this.event.from.btype = res.data.details.from_btype;
        }
        this.event.to.floors = res.data.details.to_floor;
        this.event.to.elevator = res.data.details.to_elevator;
        if(res.data.details.to_btype) {
          this.event.to.btype = res.data.details.to_btype;
        }
        this.event.comment = "";
      })
    },
    setEstimate(data) {
      if(data.event_id) {
        // 编辑
        this.event.id = data.event_id;
      } else {
        // 创建
        this.event.eventdate = data.hopedate;
        this.event.apm = data.apm;
        this.event.from.address = data.from.adr;
        this.event.from.elevator = data.from.elevator;
        if(data.from.floor) {
          this.event.from.floors = data.from.floor;
        }
        if(data.from.fbtype) {
          this.event.from.btype = data.from.fbtype;
        }
        this.event.to.address = data.to.adr;
        this.event.to.elevator = data.to.elevator;
        if(data.to.floor) {
          this.event.to.floors = data.to.floor;
        }
        if(data.to.tbtype) {
          this.event.to.btype = data.to.tbtype;
        }
        this.event.phone = data.tel;
        if(data.nickname) {
          this.event.phone += ',' + data.nickname;
        }
        if(data.partner) {
          this.event.partner = data.partner;
        }
        this.event.order_id = data.id;
        if(data.items.note) {
          this.event.comment = "客户留言:"+data.items.note+' ';
        }
        if(data.manager_note) {
          this.event.comment += "备注:"+data.manager_note+' ';
        }
        for(var key in data.images) {
          this.event.filethumbs.push(data.images[key]);
          this.event.files.push(data.images[key].replace("_thumb", ""));
        }
        if(this.event.files.length > 0) {
          this.hasFile = true;
        }
      }
    },
    setPerductListId(id) {
      var self = this;
      setInterval(function() {
        self.event.product_list_id = id;
      },500)
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
    truncate: function (value, length) {
      if (!value) return ''
      var length = length ? parseInt(length, 10) : 20
      if(value.length <= length) {
        return value
      }
      else {
        return '...' + value.substring(value.length - length)
      }
    }
  }
}

Vue.component('modal', {
  template: '#modal-template',
  props: ['warning'], 
  data() {
    return {

    }
  },
  methods: {
    setSure() {
      this.$emit('sure');
    }
  }
})
</script>

<style>
.checkbox-inline {
  margin-left: 10px;
}
.label {
  font-size: 13px;
  margin: 1px;
}
.xdsoft_datetimepicker .xdsoft_timepicker.active {
  display: none;
}
</style>