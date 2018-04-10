<template>
  <div>
    <form id="eventform" method="post">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{{ $t('event.addEvent') }}</h3>
        </div>
        <div class="box-body">
          <div class="col-xs-12 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-tags"></i></span>
              <select2 :options="options1" id="worktype" :placeholder="$t('event.workType')"></select2>
            </div>
          </div>
          <div class="col-xs-12 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-cube"></i></span>
              <select2 :options="options4" id="aboutgoods" :placeholder="$t('event.aboutgoods')"></select2>
            </div>
          </div>
          <div class="col-xs-12 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-exclamation-triangle"></i></span>
              <select2 :options="options2" id="careful" :placeholder="$t('event.typeofcareful')"></select2>
            </div>
          </div>
          <div class="col-xs-12 padding0"> 
            <div class="col-xs-6 padding0">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                <select2 :options="options3" id="total" :placeholder="$t('event.typeoftotal')"></select2>
              </div>
            </div>
            <div class="col-xs-6 padding0">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                <select2 :options="options9" id="truck" :placeholder="$t('event.typeoftruck')"></select2>
              </div>
            </div>
          </div> 
          <!-- datepicker --> 
          <div class="col-xs-6 padding0">
            <div class="input-group"> 
              <div class="input-group-addon"> 
                <i class="fa fa-calendar"></i> 
              </div> 
              <input type="text" name="eventdate" class="form-control datepicker" v-model="event.eventdate" :placeholder="$t('event.eventdate')" readonly /> 
            </div>
          </div>
          <div class="col-xs-6 padding0">
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
          <div class="col-xs-6 padding0">
            <div class="input-group"> 
              <div class="input-group-addon"> 
                <input type="radio" name="apm" value="5" v-model="event.apm" /> 
              </div> 
              <input type="text" id="stime" class="form-control datepickertime" disabled v-model="event.fromtime" /> 
              <div class="input-group-addon"> 
                〜
              </div> 
              <input type="text" id="ttime" class="form-control datepickertime" disabled v-model="event.totime" /> 
            </div> 
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-male"></i></span>
              <input type="number" class="form-control" name="partner" v-model.number="event.partner" :placeholder="$t('event.partnerID')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-jpy"></i></span>
              <input type="number" class="form-control" name="amount" v-model.number="event.amount" :placeholder="$t('event.amount')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
              <input type="number" class="form-control" name="shoppingid" v-model.number="event.shoppingid" :placeholder="$t('event.shoppingListId')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-phone"></i></span>
              <input type="text" class="form-control" name="phone" v-model="event.phone" :placeholder="$t('event.phoneNumber')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-wechat"></i></span>
              <input type="text" class="form-control" name="wechat" v-model="event.wechat" :placeholder="$t('event.wechat')">
            </div>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-sign-out"></i></span>
            <input type="text" class="form-control" name="fromadd" v-model="event.fromadd" :placeholder="$t('event.fromAddress')">
            <span class="input-group-btn">
              <button type="button" class="btn btn-info btn-flat" @click="showModal = true">{{ setDetailsFrom }}</button>
            </span>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-sign-in"></i></span>
            <input type="text" class="form-control" name="toadd" v-model="event.toadd" :placeholder="$t('event.toAddress')">
            <span class="input-group-btn">
              <button type="button" class="btn btn-info btn-flat" @click="showModal = true">{{ setDetailsTo }}</button>
            </span>
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="3" name="comment" v-model="event.comment" :placeholder="$t('event.comment')"></textarea>
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
          <router-link to="/admin"><button type="button" class="btn btn-default">{{ $t('global.Cancel') }}</button></router-link> 
          <button @click="checkForm" type="button" class="btn btn-primary pull-right">{{ $t('global.Submit') }}</button> 
        </div>
      </div>
    </form>
    <modal v-if="showModal" @close="showModal = false">
      <h4 slot="header">{{ $t('event.setDetails') }}</h4>
      <div slot="body">
        <div class="form-group">
          <label>{{ $t('event.fromAddress') }}</label>
          <label>
            <input type="radio" name="fromdt" value="1">
            {{ $t('event.elevator') }}
          </label>
          <label>
            <input type="radio" name="fromdt" value="2">
            {{ $t('event.stairs') }}
          </label>
          <label>
            <input type="radio" name="fromdt" value="3">
            {{ $t('event.elevatorAndstairs') }}
          </label>
          <label>{{ $t('event.floors') }}</label>
          <select name="fromlc">
            <option v-for="floor of floors" :value="floor"><div v-if="floor > 9">{{ floor }}+</div><div v-else>{{ floor }}</div></option>
          </select>
        </div>
        <div class="form-group">
          <label>{{ $t('event.toAddress') }}</label>
          <label>
            <input type="radio" name="todt" value="1">
            {{ $t('event.elevator') }}
          </label>
          <label>
            <input type="radio" name="todt" value="2">
            {{ $t('event.stairs') }}
          </label>
          <label>
            <input type="radio" name="todt" value="3">
            {{ $t('event.elevatorAndstairs') }}
          </label>
          <label>{{ $t('event.floors') }}</label>
          <select name="tolc">
            <option v-for="floor of floors" :value="floor"><div v-if="floor > 9">{{ floor }}+</div><div v-else>{{ floor }}</div></option>
          </select>
        </div>
      </div>
    </modal>
    <script type="text/x-template" id="modal-template">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">

              <div class="modal-header">
                <slot name="header">
                  default header
                </slot>
              </div>

              <div class="modal-body">
                <slot name="body">
                  default body
                </slot>
              </div>

              <div class="modal-footer">
                <slot name="footer">
                  <button class="modal-default-button" @click="$emit('close')">
                    OK
                  </button>
                </slot>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </script>
  </div>
</template>
<script>
var $ = window.jQuery = require('jquery')
import Vue from 'vue'
import Select2 from './Select2'
import moment from 'moment'
import datetimepicker from 'jquery-datetimepicker'
 
export default {
  mounted() {
    this.setDatePicker()
    $('input[name="apm"]:radio').change( function() {
      if($( this ).val() == 5){
        $('.datepickertime').attr('disabled', false)
      } else {
        $('.datepickertime').attr('disabled', true);
        $('input[name="stime"]').val("");
        $('input[name="ttime"]').val("");
      }
    })
    this.get_types()
    if(this.$route.params.eventdate){
      this.event.eventdate = this.$route.params.eventdate;
    }
  },
  components: {
    Select2
  },
  data() {
    return {
      options1: [],
      options2: [],
      options3: [],
      options4: [],
      options9: [],
      value: [],
      placeholder: "",
      errors: [],
      showModal: false,
      floors: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '20', '30', '40', '50'],
      setDetailsFrom: this.$i18n.t('event.setDetails'),
      setDetailsTo: this.$i18n.t('event.setDetails'),
      event: {
        eventdate: "",
        apm: "",
        fromtime: "",
        totime: "",
        worktype: [],
        aboutgoods: [],
        careful: [],
        total: [],
        truck: [],
        wechat: "",
        phone: "",
        partner: null,
        amount: null,
        shoppingid: null,
        fromadd: "",
        toadd: "",
        comment: ""
      }
    }
  },
  methods: {
    setDatePicker() {
      const self = this
 
      $.datetimepicker.setLocale('zh')
      $('.datepicker').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('.datepickertime').datetimepicker({datepicker:false, format:'H:i'})
      $('.datepicker').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.eventdate = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.event.eventdate = '';
        }
      })
      $('#ttime').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.totime = $(e.target).val()
        } else {
          self.event.totime = '';
        }
      })
      $('#stime').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.fromtime = $(e.target).val()
        } else {
          self.event.fromtime = '';
        }
      })
    },
    get_types() {
        this.$http({
            url: '/api/get_types/',
            method: 'GET'
        }).then(res =>  {
            for (var index in res.data) {
              var group_id = res.data[index].group_id;
              var option = {};
              option.id = res.data[index].id;
              option.text = res.data[index].name;
              switch (group_id) {
                case "1":
                  this.options1.push(option);
                  break;
                case "2":
                  this.options2.push(option);
                  break;
                case "3":
                  this.options3.push(option);
                  break;
                case "4":
                case "5":
                case "6":
                case "7":
                case "8":
                  this.options4.push(option);
                  break;
                case "9":
                  this.options9.push(option);
                  break;
                default:
                  break;
              }
            }
        })
    },
    checkForm:function(e) {
      this.errors = [];
      if(!this.event.eventdate){
         this.errors.push(this.$i18n.t('event.eventdate') + this.$i18n.t('global.required'));
         return;
      }
      if(!this.event.apm){
         this.errors.push(this.$i18n.t('event.apmAllDayOrTime') + this.$i18n.t('global.moreThanOne'));
         return;
      }
      if(this.event.apm == 5 && !this.event.fromtime && !this.event.totime){
         this.errors.push(this.$i18n.t('event.eventtimeChecked') + this.$i18n.t('event.eventtime') + this.$i18n.t('global.required'));
         return;
      }
      e.preventDefault();
      this.event.worktype = $('#worktype').val();
      this.event.aboutgoods = $('#aboutgoods').val();
      this.event.careful = $('#careful').val();
      this.event.total = $('#total').val();
      this.event.truck = $('#truck').val();
      this.$http.post('/admin/event', this.event).then(response => {
        console.log(response);
      }).catch(error => {
        this.errors.push(this.$i18n.t('global.calltheadministrator'))
      });
    }
  },
  created() {

  }
}

Vue.component('modal', {
  template: '#modal-template',
  data() {
    return {
      
    }
  },
  methods: {
    
  }
})
</script>
