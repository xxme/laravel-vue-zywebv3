<template>
  <div>
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{{ $t('event.attendancetitle') }}</h3>
      </div>
      <div class="box-body">
        <!-- datepicker --> 
        <div class="col-xs-6 no-padding">
          <div class="input-group"> 
            <div class="input-group-addon"> 
              <i class="fa fa-calendar"></i> 
            </div> 
            <input type="text" name="eventdatefrom" class="form-control datepickerfrom" v-model="event.eventdatefrom" :placeholder="$t('event.eventdatefrom')" readonly /> 
          </div>
        </div>
        <!-- datepicker --> 
        <div class="col-xs-6 no-padding">
          <div class="input-group"> 
            <div class="input-group-addon"> 
              〜
            </div> 
            <input type="text" name="eventdateto" class="form-control datepickerto" v-model="event.eventdateto" :placeholder="$t('event.eventdateto')" readonly /> 
          </div>
        </div>
        <div class="col-xs-12 no-padding">
          <div class="input-group checkboxgroup">
            <label class="checkbox-inline">
              <input type="checkbox" value="1" v-model="event.checkedWeek"> {{ $t('event.Monday') }}
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="2" v-model="event.checkedWeek"> {{ $t('event.Tuesday') }}
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="3" v-model="event.checkedWeek"> {{ $t('event.Wednesday') }}
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="4" v-model="event.checkedWeek"> {{ $t('event.Thursday') }}
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="5" v-model="event.checkedWeek"> {{ $t('event.Friday') }}
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="6" v-model="event.checkedWeek"> {{ $t('event.Saturday') }}
            </label>
            <label class="checkbox-inline">
              <input type="checkbox" value="0" v-model="event.checkedWeek"> {{ $t('event.Sunday') }}
            </label>
          </div>
        </div>
        
        <div class="col-xs-12 no-padding checkboxgroup">
          <div class="col-xs-6 no-padding">
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
                  <input type="radio" name="apm" value="4" v-model="event.apm">
                  {{ $t('event.alldayT') }} 
                </label>
              </div>
            </div> 
          </div>
          <div class="col-xs-6 no-padding">
            <div class="input-group"> 
              <div class="radio padding-left3">
                <label>
                  <input type="radio" name="attendance" value="1" v-model="event.attendance">
                  {{ $t('event.vacation') }} 
                </label>
                <label>
                  <input type="radio" name="attendance" value="2" v-model="event.attendance">
                  {{ $t('event.attendance') }} 
                </label>
              </div>
            </div> 
          </div>
        </div>
        <div class="col-xs-12 no-padding">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-male"></i></span>
            <div class="checkedgroup" @click="showTypeModal = true">
              <template v-if="selected.user.length > 0">
                <span class="label label-success" v-for="(type, key) in selected.user"><i class="fa fa-male"></i> {{ type }}</span>
              </template>
              <template v-else>
                <span class="inputplaceholder">{{ $t('global.partner') }}</span>
              </template>
            </div>
          </div>
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
        <button type="button" class="btn btn-default" @click="$emit('closeform')">{{ $t('global.Cancel') }}</button>
        <button @click="checkForm" type="button" class="btn btn-primary pull-right">{{ $t('global.Submit') }}</button> 
      </div>
    </div>
    
    <modal v-if="showTypeModal" @close="showTypeModal = false">
      <h4 slot="header">{{ $t('event.selectuser') }}<button type="button" class="close" @click="showTypeModal = false"><span>×</span></button></h4>
      <div slot="body">
        <label class="checkbox-inline" v-for="(user, key) in userlist">
          <input type="checkbox" v-model="clickedpartner" :value="user"> {{ user.text }}
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
                <slot name="header"></slot>
              </div>
              <div class="modal-body">
                <slot name="body"></slot>
              </div>
              <div class="modal-footer text-left">
                <slot name="footer"></slot>
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
import Loading from '../Public/Loading'
import moment from 'moment'
import datetimepicker from 'jquery-datetimepicker'

export default {
  props: ['userlist'],
  mounted() {
    this.setDatePicker()
  },
  components: {
    Loading
  },
  data() {
    return {
      errors: [],
      showModal: false,
      showTypeModal: false,
      loadingShow: false,
      warning: {
        message: "",
        action: "",
        sure: true
      },
      event: {
        apm: "4",
        attendance: "",
        checkedWeek: [],
        eventdatefrom: "",
        eventdateto: "",
        partner: [],
        comment: ""
      },
      selected: {
        user: []
      },
      clickedpartner: []
    }
  },
  watch: {
    clickedpartner: function(val) {
      var listvalue = [];
      var listtext = [];
      for(var i in val) {
        listvalue.push(val[i].id.toString());
        listtext.push(val[i].text);
      }
      this.setType(listvalue, listtext);
    }
  },
  computed: {
    eventapm: function() {
      return this.event.apm;
    }
  },
  methods: {
    setDatePicker() {
      const self = this
      $.datetimepicker.setLocale('zh')
      $('.datepickerfrom').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('.datepickerto').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('.datepickerfrom').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.eventdatefrom = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.event.eventdatefrom = '';
        }
      })
      $('.datepickerto').on('change', (e) => {
        if($(e.target).val() != ''){
          self.event.eventdateto = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.event.eventdateto = '';
        }
      })
    },
    checkForm:function(e) {
      this.errors = [];
      if(!this.event.eventdatefrom || !this.event.eventdateto){
        this.errors.push(this.$t('event.eventdatefrom') +'/'+ this.$t('event.eventdateto') + this.$t('global.required'));
      }
      if(this.event.checkedWeek.length == 0) {
        this.errors.push(this.$t('event.checkedWeek') + this.$t('global.required'));
      }
      if(!this.event.attendance){
        this.errors.push(this.$t('event.attendance') +'/'+ this.$t('event.vacation') + this.$t('global.required'));
      }
      if(this.event.partner.length == 0) {
        this.errors.push(this.$t('global.partner') + this.$t('global.required'));
      }
      if(moment(this.event.eventdatefrom) > moment(this.event.eventdateto)) {
        this.errors.push(this.$t('event.timereverse'));
      }
      if(this.errors.length == 0) {
        this.submitForm();
      }
    },
    submitForm() {
      var attendance = '';
      var weekname = '';
      var apmname = '';
      if(this.event.attendance == 1) {
        attendance = this.$t('event.vacation');
      } else {
        attendance = this.$t('event.attendance');
      }
      for(var key in this.event.checkedWeek) {
        if(this.event.checkedWeek[key] == 1) {
          weekname = this.$t('event.Monday');
        }
        if(this.event.checkedWeek[key] == 2) {
          if(weekname == '') {
            weekname = this.$t('event.Tuesday');
          } else {
            weekname += ',' + this.$t('event.Tuesday');
          }
        }
        if(this.event.checkedWeek[key] == 3) {
          if(weekname == '') {
            weekname = this.$t('event.Wednesday');
          } else {
            weekname += ',' + this.$t('event.Wednesday');
          }
        }
        if(this.event.checkedWeek[key] == 4) {
          if(weekname == '') {
            weekname = this.$t('event.Thursday');
          } else {
            weekname += ',' + this.$t('event.Thursday');
          }
        }
        if(this.event.checkedWeek[key] == 5) {
          if(weekname == '') {
            weekname = this.$t('event.Friday');
          } else {
            weekname += ',' + this.$t('event.Friday');
          }
        }
        if(this.event.checkedWeek[key] == 6) {
          if(weekname == '') {
            weekname = this.$t('event.Saturday');
          } else {
            weekname += ',' + this.$t('event.Saturday');
          }
        }
        if(this.event.checkedWeek[key] == 7) {
          if(weekname == '') {
            weekname = this.$t('event.Sunday');
          } else {
            weekname += ',' + this.$t('event.Sunday');
          }
        }
      }
      if(this.event.apm == '1') {
        apmname = this.$t('event.morning');
      } else if(this.event.apm == '2') {
        apmname = this.$t('event.afternoon');
      } else {
        apmname = this.$t('event.alldayT');
      }
      if(window.confirm(this.$t('event.attendancetitle') + '\n'
            + this.event.eventdatefrom + '〜' + this.event.eventdateto +' '+ weekname + '\n'
            + this.selected.user +' '+ apmname + attendance)) {
        this.loadingShow = true;
        this.$http.post('/admin/event/batchattendance', this.event).then(response => {
          this.loadingShow = false;
          if(response.data.status == 'error') {
            alert(response.data.message);
          } else {
            alert(this.$t('global.success'));
            location.reload();
          }
        }).catch(error => {
          this.errors.push(this.$t('global.calltheadministrator'));
          this.loadingShow = false;
        });
      }
    },
    setType(listvalue, listtext) {
      this.event.partner = listvalue;
      this.selected.user = listtext;
    },
    setLoading(switchTF) {
      this.loadingShow = switchTF;
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
  margin-left: 5px;
}
.checkboxgroup {
  padding: 6px 0;
  border: 1px solid #ccc;
}
.label {
  font-size: 13px;
  margin: 1px;
}
</style>