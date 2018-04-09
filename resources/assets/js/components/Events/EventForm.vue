<template>
  <div>
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
        <div class="form-group"> 
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
            <input type="text" name="sdate" class="form-control datepicker" v-model="eventdate" :placeholder="$t('event.scheduledDatetime')" /> 
          </div>
        </div>
        <div class="col-xs-6 padding0">
          <div class="input-group"> 
            <div class="radio padding-left15">
              <label>
                <input type="radio" name="apm[]" value="1">
                午前中
              </label>
              <label>
                <input type="radio" name="apm[]" value="2">
                午後
              </label>
              <label>
                <input type="radio" name="apm[]" value="3">
                夜
              </label>
            </div>
          </div> 
        </div>
        <div class="col-xs-6 padding0">
          <div class="input-group"> 
            <div class="input-group-addon"> 
              <input type="radio" name="apm[]" value="4" /> 
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
            <input type="text" class="form-control" placeholder="Partner ID">
          </div>
        </div>
        <div class="col-xs-6 padding0">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
            <input type="text" class="form-control" placeholder="Phone number">
          </div>
        </div>
        <div class="col-xs-6 padding0">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-wechat"></i></span>
            <input type="text" class="form-control" placeholder="Wechat">
          </div>
        </div>
        <div class="col-xs-6 padding0">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-jpy"></i></span>
            <input type="text" class="form-control" placeholder="Amount">
          </div>
        </div>
        <div class="col-xs-6 padding0">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
            <input type="text" class="form-control" placeholder="Shopping list ID">
          </div>
        </div>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-sign-out"></i></span>
          <input type="text" class="form-control" placeholder="From address">
        </div>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-sign-in"></i></span>
          <input type="text" class="form-control" placeholder="To address">
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <router-link to="/admin"><button type="submit" class="btn btn-default">{{ $t('global.Cancel') }}</button></router-link> 
        <button type="submit" class="btn btn-primary pull-right">{{ $t('global.Submit') }}</button> 
      </div>
    </div>
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
      if($( this ).val() == 4){
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
      eventfromtime: "",
      eventtotime: "",
      placeholder: ""
    }
  },
  methods: {
    setDatePicker() {
      const self = this
 
      $.datetimepicker.setLocale('zh')
      $('.datepicker').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('.datepickertime').datetimepicker({datepicker:false, format:'H:i'})
      // $('.datepicker').on('change', (e) => {
      //   self.eventdate = moment($(e.target).val()).format('YYYY-MM-DD')
      // })
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
    }
  },
  created() {

  }
}
</script>
