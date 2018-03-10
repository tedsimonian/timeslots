<template>


    <div class="row">


            <div class="col-md-8" v-if="can_calendar">

                <div class="card">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-12">
                                <h3>Legends</h3>

                            </div>
                            <hr>
                            <div class="col-md-3">
                                <span class="appointment legend-small"></span><p>Appointment</p>
                            </div>
                            <div class="col-md-3">

                                <span class="selected legend"></span><p>Selected Date</p>

                            </div>
                            <div class="col-md-3">
                                <span class="special legend"></span><p>Special Day</p>
                            </div>
                            <div class="col-md-3">
                                <span class="off legend"></span><p>Special Day Off</p>
                            </div>


                        </div>
                        <div class="row">

                            <div class="col-md-3">

                                <span class="event legend-small"></span><p>Event</p>

                            </div>



                            <div class="col-md-3">

                                <span class="fully legend"></span><p>Fully Booked</p>

                            </div>


                        </div>
                        <hr>
                        <v-date-picker
                                @input="selectDate"
                                :attributes='attributes'
                                is-inline
                                is-expanded
                                is-double-paned
                                mode='single'
                                :min-date="minDate"
                                v-model='selectedDate'
                                :disabled-dates='disabled_dates'
                                :available-dates='available_days'
                                show-caps>

                        </v-date-picker>
                        <hr>

                        <div class="row" v-if="events_day.length>0">

                            <div class="col-md-12">

                                <h3>Appointments</h3>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>User</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item,index) in events_day">
                                        <td>{{item.id}}</td>
                                        <td>{{item.day}}</td>
                                        <td>
                                            {{item.timeslot}} - {{endTime(item.timeslot,item.duration)}}
                                        </td>
                                        <td>
                                            {{item.duration}}
                                        </td>
                                        <td>
                                            ${{item.price}}
                                        </td>
                                        <td>
                                            {{item.user.first_name}} {{item.user.last_name}}
                                        </td>
                                        <td>
                                            <span class="status" :class="item.status">{{item.status}}</span>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                        <div class="row" v-if="customEvents_day.length>0">

                            <div class="col-md-12">

                                <h3>Events</h3>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Title</th>
                                        <th>Color</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item,index) in customEvents_day">
                                        <td>{{item.id}}</td>
                                        <td>{{item.day}}</td>
                                        <td>
                                            {{item.start}} - {{item.end}}
                                        </td>
                                        <td>
                                            {{item.title}}
                                        </td>
                                        <td>
                                            <span class="status" :style="{backgroundColor:item.color}">{{item.color}}</span>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>



                    </div>

                </div>



            </div>

        <div class="col-md-4" v-if="can_calendar && can_book">

            <div class="card">

                <div class="card-body">

                    <div class="selected-date">
                        <h3>Selected Date:{{selectedDateFormated}}</h3>
                        <p>Event Duration:{{event_duration}}</p>
                        <p>Price:${{event.price}}</p>
                        <hr>
                        <div v-if="form_selected_days.selected.length==0 && type=='user'">

                            <p>Please select an available timeslot!</p>
                        </div>
                        <div v-else>

                            <div class="row">
                                <div class="col-md-12">

                                    <table class="table table-bordered" v-if="type=='user'">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th class="text-nowrap">Remove</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item,index) in form_selected_days.selected">
                                            <td>{{index+1}}</td>
                                            <td>{{formDate}}</td>
                                            <td>
                                                {{item}} - {{endTime(item,event_duration)}}
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="#"  @click="removeEvent(item)" data-toggle="tooltip" data-original-title="Close"> <i class="fa fa-close text-danger"></i> </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered" v-if="type=='event' && form_selected_event_days.selected.end_time!='' && form_selected_event_days.selected.start_time!=''">
                                        <thead>
                                        <tr>

                                            <th>Date</th>
                                            <th>Time</th>
                                            <th class="text-nowrap">Remove</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>

                                            <td>{{formDate}}</td>
                                            <td>
                                                {{form_selected_event_days.selected.start_time}} - {{form_selected_event_days.selected.end_time}}
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="#"  @click="removeEvent()" data-toggle="tooltip" data-original-title="Close"> <i class="fa fa-close text-danger"></i> </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                            <div class="row">
                                <label class="col-md-12">Choose type of event:</label>

                                <div class="col-md-12">

                                    <input name="user" type="radio" class="with-gap" id="user" value="user" v-model="type"/>
                                    <label for="user">User Appointment</label>
                                    <input name="event" type="radio" id="event" class="with-gap" value="event" v-model="type" @change="eventPicked"/>
                                    <label for="event">Event</label>

                                </div>
                            </div>
                            <hr>
                            <p v-if="type=='event' && (form_selected_event_days.selected.start_time==''|| form_selected_event_days.selected.start_time==null)">Please pick event start time!</p>
                            <p v-if="type=='event' && (form_selected_event_days.selected.end_time=='' || form_selected_event_days.selected.end_time==null)">Please pick event end time!</p>
                            <hr>
                            <div class="row">

                                <div class="col-md-12">

                                    <button type="submit" class="btn btn-success" @click="eventSelect" :disabled="type=='event' && (form_selected_event_days.selected.start_time==''|| form_selected_event_days.selected.start_time==null || form_selected_event_days.selected.end_time==''|| form_selected_event_days.selected.end_time==null)">Select</button>

                                </div>

                            </div>



                        </div>
                        <hr>
                        <div class="row"  v-if="form_specific_days.availability.length!=0" style="padding-left:15px;padding-right:15px;">


                            <div class="col-md-3"  style="padding-right:0px;padding-left:0px" v-for="n in form_specific_days.availability">

                                                 <span @click="toggleDaySlot(n)" class="timeslot-special" :class="{'available':checkSelected(n),'unavailable':checkAvailabilityDay(n),'inactive':checkInactive(n)}">


                                                    {{n}}


                                                  </span>





                            </div>


                        </div>
                        <div class="row" v-else >
                            <div class="col-md-12" v-if="form_specific_days.availability.length==0">

                                <p>All timeslots are booked!</p>

                            </div>
                        </div>




                    </div>

                </div>

            </div>







        </div>





        <modal name="create-appointment" height="auto" :scrollable="true">
            <div class="modal-body">
                <h3>Create Appointment</h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">

                        <h4 v-if="form_selected_days.selected.length==0">Please select an available timeslot!</h4>
                        <table class="table table-bordered" v-if="form_selected_days.selected.length!=0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <td>Date</td>
                                <th>Time</th>
                                <th class="text-nowrap">Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,index) in form_selected_days.selected">
                                <td>{{index+1}}</td>
                                <td>{{formDate}}</td>
                                <td>
                                    {{item}} - {{endTime(item,event_duration)}}
                                </td>
                                <td class="text-nowrap">
                                    <a href="#"  @click="removeEvent()" data-toggle="tooltip" data-original-title="Close"> <i class="fa fa-close text-danger"></i> </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
                <div class="row" v-if="form_selected_days.selected.length!=0">

                    <div class="col-md-6">

                        <div class="form-group" >
                            <label class="col-md-12">User</label>
                            <div class="col-md-12">

                                <model-list-select :list="users"
                                                   option-value="id"
                                                   option-text="name"
                                                   v-model="event.user"
                                                   placeholder="Select user..."
                                                   @searchchange="searchUser">
                                </model-list-select>

                            </div>
                        </div>


                    </div>

                </div>
                <hr>
                <div class="row">

                    <div class="col-md-6">



                                <button type="submit" class="btn btn-success" :disabled="form_selected_days.selected.length==0 || isEmpty(event.user)" @click="createAppointment">Submit</button>

                                <button type="submit" class="btn btn-warning" @click="hide">Close</button>


                    </div>

                </div>

            </div>

        </modal>


        <modal name="create-event" height="auto" :scrollable="true">
            <div class="modal-body">
                <h3>Create Event</h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">

                        <h4 v-if="form_selected_event_days.selected.end_time=='' && form_selected_event_days.selected.start_time==''">Please select start and end time!</h4>
                        <table class="table table-bordered" v-if="form_selected_event_days.selected.end_time!='' && form_selected_event_days.selected.start_time!=''">
                            <thead>
                            <tr>

                                <th>Date</th>
                                <th>Time</th>
                                <th class="text-nowrap">Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr >
                                <td>{{formDate}}</td>
                                <td>
                                    {{form_selected_event_days.selected.start_time}} - {{form_selected_event_days.selected.end_time}}
                                </td>
                                <td class="text-nowrap">
                                    <a href="#"  @click="removeEvent()" data-toggle="tooltip" data-original-title="Close"> <i class="fa fa-close text-danger"></i> </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
                <div class="row" v-if="form_selected_event_days.selected.end_time!='' && form_selected_event_days.selected.start_time!=''">

                    <div class="col-md-6">

                        <div class="form-group" >
                            <label class="col-md-12">Event Title</label>
                            <div class="col-md-12">

                                <input type="text" class="form-control" v-model="eventCustom.title" required>

                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">

                        <div class="form-group" >
                            <label class="col-md-12">Event Color</label>
                            <div class="col-md-12">

                                <colorpicker :color="eventCustom.color" v-model="eventCustom.color" />

                            </div>
                        </div>


                    </div>



                </div>
                <div class="row" v-if="form_selected_event_days.selected.end_time!='' && form_selected_event_days.selected.start_time!=''">

                    <div class="col-md-12">

                        <div class="form-group" >
                            <label class="col-md-12">Event Description</label>
                            <div class="col-md-12">

                                <textarea class="form-control" cols="5" rows="4" style="width:100%" v-model="eventCustom.description" required></textarea>

                            </div>
                        </div>


                    </div>



                </div>
                <hr>
                <div class="row">

                    <div class="col-md-6">



                        <button type="submit" class="btn btn-success" :disabled="(form_selected_event_days.selected.end_time=='' && form_selected_event_days.selected.start_time=='') || eventCustom.title=='' || eventCustom.description==''" @click="createEvent">Submit</button>

                        <button type="submit" class="btn btn-warning" @click="hide">Close</button>


                    </div>

                </div>

            </div>

        </modal>

    </div>






</template>
<script>

    import VCalendar from 'v-calendar';
    import 'v-calendar/lib/v-calendar.min.css';
    import moment from 'moment';
    import VModal from 'vue-js-modal';
    import { ModelListSelect } from 'vue-search-select';
    import { Compact } from 'vue-color'

    Vue.use(VModal);


    Vue.component('colorpicker', {
        components: {
            'compact-picker': Compact,
        },
        template: `
<div class="input-group color-picker" ref="colorpicker">
	<input type="text" class="form-control" v-model="colorValue" @focus="showPicker()" @input="updateFromInput" disabled/>
	<span class="input-group-addon color-picker-container">
		<span class="current-color" :style="'background-color: ' + colorValue" @click="togglePicker()"></span>
		<compact-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" :palette="palette"/>
	</span>
</div>`,
        props: ['color'],
        data() {
            return {
                colors: {
                    hex: '#DC2127',
                },
                palette:['#A4BDFC','#7AE7BF','#DBADFF','#FF887C','#FBD75B','#FFB878','#46D6DB','#E1E1E1','#5484ED','#51B749','#DC2127'],
                colorValue: '',
                displayPicker: false,
            }
        },
        mounted() {
            this.setColor(this.color || '#DC2127');
        },
        methods: {
            setColor(color) {
                this.updateColors(color);
                this.colorValue = color;
            },
            updateColors(color) {
                if(color.slice(0, 1) == '#') {
                    this.colors = {
                        hex: color
                    };
                }
                else if(color.slice(0, 4) == 'rgba') {
                    var rgba = color.replace(/^rgba?\(|\s+|\)$/g,'').split(','),
                        hex = '#' + ((1 << 24) + (parseInt(rgba[0]) << 16) + (parseInt(rgba[1]) << 8) + parseInt(rgba[2])).toString(16).slice(1);
                    this.colors = {
                        hex: hex,
                        a: rgba[3],
                    }
                }
            },
            showPicker() {
                document.addEventListener('click', this.documentClick);
                this.displayPicker = true;
            },
            hidePicker() {
                document.removeEventListener('click', this.documentClick);
                this.displayPicker = false;
            },
            togglePicker() {
                this.displayPicker ? this.hidePicker() : this.showPicker();
            },
            updateFromInput() {
                this.updateColors(this.colorValue);
            },
            updateFromPicker(color) {
                this.colors = color;
                if(color.rgba.a == 1) {
                    this.colorValue = color.hex;
                }
                else {
                    this.colorValue = 'rgba(' + color.rgba.r + ', ' + color.rgba.g + ', ' + color.rgba.b + ', ' + color.rgba.a + ')';
                }
            },
            documentClick(e) {
                var el = this.$refs.colorpicker,
                    target = e.target;
                if(el !== target && !el.contains(target)) {
                    this.hidePicker()
                }
            }
        },
        watch: {
            colorValue(val) {
                if(val) {
                    this.updateColors(val);
                    this.$emit('input', val);
                }
            }
        },
    });

    export default{


        data(){

            return {

                users: [],
                searchText: '',
                increment:null,
                slots_num:null,
                event_duration:null,
                selectedDate: new Date(),
                minDate:new Date(),
                event:new Form({user:{},day:'',timeslot:'',duration:'',price:''}),
                eventCustom:new Form({day:'',start:'',end:'',title:'',color:'#DC2127',description:''}),
                form_specific_days:new Form({day: new Date(),available:true,availability:[]}),
                days_marker: [],
                events_marker:[],
                disabled_dates:[],
                available_days:[],
                form_selected_days:new Form({day:new Date(),selected:[]}),
                form_selected_event_days:new Form({day:new Date(),selected:{start_time:'',end_time:''}}),
                selected_events:[],
                events_day:[],
                customEvents_day:[],
                type:'user',
                hour_availability:[],
                inactive:[],
                can_calendar:true,
                can_book:true




            };
        },
        components: {VCalendar,VModal,ModelListSelect},
        computed:{


            attributes() {
                let attributes=this.days_marker.map(t => ({
                    key: `day.${t.id}`,
                    highlight: {
                        backgroundColor: t.backgroundColor,
                        borderColor:t.borderColor,
                        borderWidth:'2px',
                        borderStyle:'solid',
                        color:'white'
                    },
                    dates: t.date,
                    customData: t,
                }));


                for(let i=0;i<this.events_marker.length;i++){

                    attributes.push({

                        dot: {
                            backgroundColor: (this.events_marker[i].type=='event') ? '#794dff' : 'blue', // Purple dot
                        },
                        popover:{

                          label:(this.events_marker[i].type=='event') ? this.events_marker[i].count+ ' Appointment/s' : this.events_marker[i].count+ ' Event/s',
                        },
                        dates: this.events_marker[i].date
                    });
                }



                return attributes;

            },
            formDate(){

                return moment(this.selectedDate).format("YYYY-MM-DD");
            },
            selectedDateFormated(){


                return moment(this.selectedDate).format("MMM Do YY");
            }

        },
        mounted(){

            this.checkPermissions();
            axios.get('/employee/get-calendar').
            then(response=>{




                this.increment=response.data.increment;
                this.event_duration=response.data.event_duration;
                this.event.duration=response.data.event_duration;
                this.event.price=response.data.price;
                if(this.increment!=null){

                    this.calculateSlotsNum(this.increment);
                }

                this.getCalendarAvailability();
                this.getHourSchedule(new Date());
                this.getDaysMarkers();

            });

            this.searchUser('');



        },
        methods:{

            checkPermissions(){

                axios.get('/employee/check-permissions').
                then(response=>{


                    this.can_calendar=response.data.can_calendar;
                    this.can_book=response.data.can_book;

                });
            },
            eventPicked(){

              if(this.type=='event'){

                  this.form_selected_days.selected=[];
              }


            },
            isEmpty(obj) {
                for(let key in obj) {
                    if(obj.hasOwnProperty(key))
                        return false;
                    }
                        return true;
            },
            createAppointment(){


                let date=moment(this.form_selected_days.day).format('YYYY-MM-DD');
                this.event.day=date;
                this.event.timeslot=this.form_selected_days.selected[0];
                this.event.post('/employee/create-appointment',false)
                    .then(status=>{



                        if(status.success){


                            this.hide();
                            this.form_selected_days.selected=[];
                            this.getHourSchedule(date);
                            this.getEvents(date);
                            this.getDaysMarkers();
                            $.toast({
                                heading: 'Event',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Event',
                                text: 'Error editing  settings!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }


                    });



            },
            createEvent(){


                let date=moment(this.form_selected_days.day).format('YYYY-MM-DD');
                this.eventCustom.day=date;
                this.eventCustom.start=this.form_selected_days.selected[0];
                this.eventCustom.end=this.form_selected_days.selected[this.form_selected_days.selected.length-1];
                this.eventCustom.post('/employee/create-event',false)
                    .then(status=>{



                        if(status.success){


                            this.hide();
                            this.form_selected_days.selected=[];
                            this.getHourSchedule(date);
                            this.getEvents(date);
                            this.getDaysMarkers();
                            $.toast({
                                heading: 'Event',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Event',
                                text: 'Error saving event!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }


                    });



            },
            searchUser (searchText) {
                this.searchText = searchText;
                axios.get('/employee/get-users').
                then(response=>{

                    this.users=response.data;

                });
            },
            endTime(time,duration){

                let numbers=time.split(':');
                let hour=parseInt(numbers[0]);
                let minutes=parseInt(numbers[1]);

                let total=((hour*60)+minutes)+parseInt(duration);
                let final_hours = Math.trunc(total/60);
                let final_minutes = (total % 60)==0 ? '00' : total % 60;
				
			
				

                return  final_hours + ':' + final_minutes;
            },
            eventSelect(){


                if(this.type=='event'){

                    this.$modal.show('create-event');
                }else{


                    this.$modal.show('create-appointment');
                }


            },
            hide () {

                if(this.type=='event'){
                    this.$modal.hide('create-event');
                }else{

                    this.$modal.hide('create-appointment');
                }

            },
            removeEvent(time){

                if(this.type=='user'){

                    let index=this.form_selected_days.selected.indexOf(time);
                    this.form_selected_days.selected.splice(index,1);
                }else{

                    this.form_selected_days.selected=[];

                    this.form_selected_event_days.selected.start_time='';
                    this.form_selected_event_days.selected.end_time='';

                }




            },
            toggleDaySlot(time){

                if(!this.checkAvailabilityDay(time)){

                    return false;
                }

                if(this.type=='event'){


                    if(this.form_selected_event_days.selected.start_time!='' && this.form_selected_event_days.selected.end_time!=''){


                        this.form_selected_days.selected=[];
                        this.form_selected_event_days.day=this.selectedDate;
                        this.form_selected_event_days.selected.start_time=time;
                        this.form_selected_event_days.selected.end_time='';
                        this.form_selected_days.selected.push(time);


                        this.checkEndTime(time);
                        return false;

                    }


                    if(this.form_selected_event_days.selected.start_time!='' && this.form_selected_event_days.selected.start_time!=null){

                        this.form_selected_event_days.day=this.selectedDate;
                        this.form_selected_event_days.selected.end_time=time;

                        let start_time_index=this.form_specific_days.availability.indexOf(this.form_selected_event_days.selected.start_time);
                        let end_time_index=this.form_specific_days.availability.indexOf(this.form_selected_event_days.selected.end_time);

                        for(let i=0;i<this.form_specific_days.availability.length;i++){

                            if(i>=start_time_index && i<=end_time_index){


                                this.form_selected_days.selected.push(this.form_specific_days.availability[i]);

                            }
                        }

                        this.inactive=[];


                    }else{


                        this.form_selected_event_days.day=this.selectedDate;
                        this.form_selected_event_days.selected.start_time=time;
                        this.form_selected_days.selected.push(time);

                        this.checkEndTime(time);
                    }


                }else{

                    this.form_selected_days.day=this.selectedDate;
                    let index=this.form_selected_days.selected.indexOf(time);
                    if(index>=0){

                        this.form_selected_days.selected.splice(index,1);

                    }else{

                        this.form_selected_days.selected=[];

                        this.form_selected_days.selected.push(time);
                    }
                }





            },
            checkInactive(time){


                if(this.inactive.indexOf(time)>=0){

                    return true;
                }

                return false;

            },
            checkEndTime(start_time){



                let start=this.hour_availability.indexOf(start_time);

                let last_active_index=0;


                this.inactive=[];


                for(let i=0;i<this.hour_availability.length;i++){


                    if(i<start){

                        this.inactive.push(this.hour_availability[i]);
                    }


                }

                for(let i=start;i<this.hour_availability.length;i++){


                    if(this.form_specific_days.availability.indexOf(this.hour_availability[i])<0){



                        last_active_index=this.form_specific_days.availability.indexOf(this.hour_availability[i-1]);



                        for(let i2=last_active_index+1;i2<this.form_specific_days.availability.length;i2++){



                            this.inactive.push(this.form_specific_days.availability[i2]);

                        }


                        return false;

                    }

                }






            },
            getCalendarAvailability(){

                axios.get('/employee/get-calendar-availability').
                then(response=>{


                   this.disabled_dates.push({weekdays:response.data.disabled_weekdays});

                    if(response.data.available_days.length>0){

                        for(let i=0;i<response.data.available_days.length;i++){

                            this.available_days.push({start:response.data.available_days[i],end:response.data.available_days[i]});
                        }

                    }


                    if(response.data.disabled_days.length>0){

                        for(let i=0;i<response.data.disabled_days.length;i++){

                            this.disabled_dates.push({start:response.data.disabled_days[i],end:response.data.disabled_days[i]});
                        }

                    }


                });

            },
            calculateSlotsNum(increment){

                if(increment==60){

                    this.slots_num=1;
                }
                if(increment==45){

                    this.slots_num=4;
                }
                if(increment==30){

                    this.slots_num=2;
                }
                if(increment==15){

                    this.slots_num=4;
                }

            },
            hourFormat(hour,minutes){

                let total=((hour*60)+minutes);
                let final_hours = Math.trunc(total/60);
                let final_minutes = (total % 60)==0 ? '00' : total % 60;

                return  final_hours + ':' + final_minutes;

            },
            getHourSchedule(date){




                let new_date=moment(date).format('YYYY-MM-DD');

                let weekday=moment(new_date).weekday();
                if(weekday==0){

                    weekday=moment().isoWeekday();

                }
                let today=moment().format('YYYY-MM-DD H:mm');


                axios.get('/employee/get-hour-availability?day='+new_date+'&weekday='+weekday+'&increment='+this.increment+'&event_duration='+this.event_duration+'&today='+today).
                then(response=>{

                    this.form_specific_days.available=true;
                    this.form_specific_days.availability=[];
                    if(response.data !==undefined && response.data.availability.length!=0 && response.data.availability!=""){



                            this.form_specific_days.availability=response.data.availability;
                            this.hour_availability=response.data.hours;



                    }else{

                        this.form_specific_days.available=true;
                        this.form_specific_days.availability=[];
                    }


                });

            },
            getDaysMarkers(){

                let today=moment().format('YYYY-MM-DD H:mm');
                axios.get('/employee/get-days-markers?increment='+this.increment+'&event_duration='+this.event_duration+'&today='+today+'&rules=0').
                then(response=>{



                    this.days_marker=response.data.days_markers;
                    this.events_marker=response.data.events_markers;


                });


            },
            getEvents(date){

                let new_date=moment(date).format('YYYY-MM-DD');
                axios.get('/employee/get-events?day='+new_date).
                then(response=>{



                    this.events_day=response.data.appointments;
                    this.customEvents_day=response.data.events;

                });

            },
            selectDate(date){


                if(this.selectedDate==null){

                    this.selectedDate=new Date();

                }


                this.form_specific_days.day=date;
                this.form_selected_days={day:date,selected:[]};
                this.form_selected_event_days={day:date,selected:{start:'',end:''}};
                this.inactive=[];
                this.getHourSchedule(this.selectedDate);
                this.getEvents(this.selectedDate);



            },
            checkSelected(time){

                if(this.form_selected_days.selected===undefined || this.form_selected_days.selected.length==0){

                    return false;
                }

                if(this.form_selected_days.selected.indexOf(time)>=0){

                    return true;
                }

                return false;
            },
            checkAvailabilityDay(time){



                if(this.form_specific_days.availability===undefined || this.form_specific_days.availability.length==0){

                    return false;
                }

                if(this.form_specific_days.availability.indexOf(time)>=0){

                    return true;
                }

                return false;


            },
        }
    }


</script>
<style>

    .inactive{


        background-color: #ddd !important;
        cursor: not-allowed !important;
        color: #FFFFFF;
    }

    .inactive:hover{

        border-color: white !important;
    }

    .unavailable{

        background-color: #c3dab9;
        border-color: #c3dab9;
        color: #FFFFFF;
    }

    .unavailable:hover{

        background-color: #78CD51;
        border-color: #78CD51;
        color: #FFFFFF;
    }

    .available{

        background-color: #78CD51;
        border-color: #78CD51;
        color: #FFFFFF;
    }

    .available:hover{

        background: #c3dab9;
        border-color: #c3dab9;;
    }

    .timeslot{

        float:left;
        width: 50%;
        text-align: center;
        display: inline-block;
        vertical-align: middle;
        border: 1px solid #fff;
        cursor: pointer;
        padding:3px;
        border-radius: 3px;
    }

    .timeslot-full-width{

        width:100% !important;
    }

    table td{

        padding:5px !important;
    }

    .table td, .table th{

        text-align:center;
    }

    .hour{

        font-weight:bold;
        text-align: center;
    }

    .selected-date{

        border:1px solid rgb(218, 218, 218);
        padding:10px;
    }

    .timeslot-special{

        float:left;
        width: 100%;
        text-align: center;
        display: inline-block;
        vertical-align: middle;
        border: 1px solid #fff;
        cursor: pointer;
        padding:3px;
        border-radius: 3px;
    }


    .legend{

        float:left;
        margin-right:15px;
        display: block;
        height: 25px;
        width:25px;
        border-radius: 50%;

    }

    .legend-small{

        margin-top: 5px;
        float:left;
        margin-right:15px;
        display: block;
        height: 15px;
        width:15px;
        border-radius: 50%;

    }

    .appointment{

        background: #794dff;
    }

    .event{

        background: blue;
    }

    .selected{

        background: rgb(102, 179, 204);
    }

    .special{

        background: rgb(255, 178, 43);
    }

    .partial{

        background:green;
    }

    .fully{

        background:red;
    }

    .off{

        background: yellow;
    }

    .status{

        border-radius: 3px;
        padding: 3px;
        color:white;
    }

    .completed{

        background-color:green;
    }

    .pending{

        background-color:rgb(255, 178, 43)
    }

    .vc-compact {
        position: absolute;
        top: 35px;
        right: 0;
        z-index: 9;
    }
    .current-color {
        display: inline-block;
        width: 16px;
        height: 16px;
        background-color: #000;
        cursor: pointer;
    }

</style>