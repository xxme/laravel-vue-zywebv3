<template>
    <div>
        <todo :events="events"></todo>
    </div>
</template>
<script>
import Vue from 'vue'
Vue.component('todo', {
    template: '<div></div>',
    props: {
        events: {
            type: Array, 
            required: true
        },
    },
    data () {
        return {
            cal: null
        }
    },
    mounted () {
        var self = this
        this.cal = $(self.$el)
        
        var args = {
            defaultView: 'month',
            events: self.events,
            // defaultDate: '{{ $showdate }}',
            // 日付クリックイベント
            dayClick: function(date, jsEvent, view) {
                alert('Clicked on: ' + date.format());
            },
            // イベントクリック
            eventClick: function(calEvent, jsEvent, view) {
                alert('Event: ' + calEvent.title);
            },
            
        }
        
        this.cal.fullCalendar(args)
    }
})

export default {
    created () {
        this.get_cal();
    },
    methods: {
        get_cal() {
            this.events = [
            {
                title: 'Event1',
                start: '2018-03-14',
                color: 'green',     // an option!
                textColor: 'white'  // an option!
            },
            {
                title: 'Event2',
                start: '2018-03-22',
            },
            ];
        }
    },
}
</script>
