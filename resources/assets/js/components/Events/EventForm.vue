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
              <select2 :options="options1" :placeholder="$t('event.workType')"></select2>
            </div>
          </div>
          <div class="col-xs-12 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-cube"></i></span>
              <select2 :options="options4" :placeholder="$t('event.aboutgoods')"></select2>
            </div>
          </div>
          <div class="col-xs-12 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-exclamation-triangle"></i></span>
              <select2 :options="options2" :placeholder="$t('event.typeofcareful')"></select2>
            </div>
          </div>
          <div class="col-xs-12 padding0"> 
            <div class="col-xs-6 padding0">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-cubes"></i></span>
                <select2 :options="options3" :placeholder="$t('event.typeoftotal')"></select2>
              </div>
            </div>
            <div class="col-xs-6 padding0">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                <select2 :options="options9" :placeholder="$t('event.typeoftruck')"></select2>
              </div>
            </div>
          </div> 
          <!-- datepicker --> 
          <div class="col-xs-6 padding0">
            <div class="input-group"> 
              <div class="input-group-addon"> 
                <i class="fa fa-calendar"></i> 
              </div> 
              <input type="text" name="eventdate" class="form-control datepicker" v-model="eventdate" :placeholder="$t('event.eventdate')" readonly /> 
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group"> 
              <div class="radio padding-left15">
                <label>
                  <input type="radio" name="apm[]" value="1" v-model="apm">
                  {{ $t('event.morning') }} 
                </label>
                <label>
                  <input type="radio" name="apm[]" value="2" v-model="apm">
                  {{ $t('event.afternoon') }} 
                </label>
                <label>
                  <input type="radio" name="apm[]" value="3" v-model="apm">
                  {{ $t('event.night') }} 
                </label>
                <label>
                  <input type="radio" name="apm[]" value="4" v-model="apm">
                  {{ $t('event.allday') }} 
                </label>
              </div>
            </div> 
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group"> 
              <div class="input-group-addon"> 
                <input type="radio" name="apm[]" value="5" v-model="apm" /> 
              </div> 
              <input type="text" name="stime" class="form-control datepickertime" disabled v-model="eventfromtime" /> 
              <div class="input-group-addon"> 
                〜
              </div> 
              <input type="text" name="ttime" class="form-control datepickertime" disabled v-model="eventtotime" /> 
            </div> 
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-male"></i></span>
              <input type="text" class="form-control" :placeholder="$t('event.partnerID')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-phone"></i></span>
              <input type="text" class="form-control" name="phone" v-model="phone" :placeholder="$t('event.phoneNumber')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-wechat"></i></span>
              <input type="text" class="form-control" name="wechat" v-model="wechat" :placeholder="$t('event.wechat')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-jpy"></i></span>
              <input type="text" class="form-control" :placeholder="$t('event.amount')">
            </div>
          </div>
          <div class="col-xs-6 padding0">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
              <input type="text" class="form-control" :placeholder="$t('event.shoppingListId')">
            </div>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-sign-out"></i></span>
            <input type="text" class="form-control" :placeholder="$t('event.fromAddress')">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-sign-in"></i></span>
            <input type="text" class="form-control" :placeholder="$t('event.toAddress')">
          </div>
          <div class="form-group">
            <textarea class="form-control" rows="3" :placeholder="$t('event.comment')"></textarea>
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
    $('input[name="apm[]"]:radio').change( function() {
      if($( this ).val() == 5){
        $('.datepickertime').attr('disabled', false)
      } else {
        $('.datepickertime').attr('disabled', true);
        $('input[name="stime"]').val("");
        $('input[name="ttime"]').val("");
      }
    })
    this.get_types()
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
      eventdate: "",
      apm: "",
      eventfromtime: "",
      eventtotime: "",
      placeholder: "",
      errors: [],
      wechat: "",
      phone: "",
      isValid: true
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
          self.eventdate = moment($(e.target).val()).format('YYYY-MM-DD')
        }
      })
    },
    get_types() {
        this.$http({
            url: '/api/get_types/',
            method: 'GET'
        }).then(res =>  {
            for (let index = 0; index < res.data.length; index++) {
              var group_id = res.data[index].group_id;
              var option = {};
              option.id = res.data[index].id;
              option.text = res.data[index].name;
              switch (group_id) {
                case 1:
                  this.options1.push(option);
                  break;
                case 2:
                  this.options2.push(option);
                  break;
                case 3:
                  this.options3.push(option);
                  break;
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                  this.options4.push(option);
                  break;
                case 9:
                  this.options9.push(option);
                  break;
                default:
                  break;
              }
            };
        })
    },
    checkForm:function(e) {
      this.errors = [];
      if(!this.eventdate){
         this.errors.push(this.$i18n.t('event.eventdate')+this.$i18n.t('global.required'));
         return;
      }
      if(!this.apm){
         this.errors.push(this.$i18n.t('event.apmAllDayOrTime')+this.$i18n.t('global.moreThanOne'));
         return;
      }
      if(this.apm == 5 && !this.eventfromtime && !this.eventtotime){
         this.errors.push(this.$i18n.t('event.eventtimeChecked')+this.$i18n.t('event.eventtime')+this.$i18n.t('global.required'));
         return;
      }
      e.preventDefault();
      let formData = new FormData(e.target);
      this.$http.post('/admin/event/add_do', formData).then(response => {
        console.log(response);
      }).catch(error => {
        this.errors.push(this.$i18n.t('global.calltheadministrator'))
      });
    }
  },
  created() {

  }
}
</script>
