<template>


    <div class="row">

        <div class="col-lg-12 col-xlg-12 col-md-12">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#rules" role="tab">Rules</a> </li>
                    <li class="nav-item "> <a class="nav-link"  :class="{'disabled':increment==null}" data-toggle="tab" href="#working" role="tab">Working Days Schedule</a> </li>
                    <li class="nav-item"> <a class="nav-link" :class="{'disabled':increment==null}" data-toggle="tab" href="#special" role="tab">Special Days Schedule</a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="rules" role="tabpanel">
                        <div class="card-body">



                            <form id="settings" class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

                                <div class="form-body">

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group" :class="{'has-danger':form.errors.has('increment')}">
                                                <label class="col-md-12">Timeslot Increment</label>
                                                <div class="col-md-12">

                                                    <select class="form-control custom-select" name="increment" v-model="form.increment" required >
                                                        <option value=""  disabled>Please choose timeslot increment...</option>
                                                        <option value="15" >15</option>
                                                        <option value="30" >30</option>
                                                        <option value="45" >45</option>
                                                        <option value="60" >60</option>



                                                    </select>
                                                    <small class="form-control-feedback" v-if="form.errors.has('increment')"> <strong v-text="form.errors.get('increment')"></strong></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group" :class="{'has-danger':form.errors.has('event_duration')}">
                                                <label class="col-md-12">Event Duration</label>
                                                <div class="col-md-12">
                                                    <select class="form-control custom-select" name="event_duration"  v-model="form.event_duration" required >
                                                        <option value=""  disabled>Please choose event duration...</option>
                                                        <option value="15" >15</option>
                                                        <option value="30" >30</option>
                                                        <option value="45" >45</option>
                                                        <option value="60" >60</option>

                                                    </select>
                                                    <small class="form-control-feedback" v-if="form.errors.has('event_duration')"> <strong v-text="form.errors.get('event_duration')"></strong></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">



                                                <div class="form-group" :class="{'has-danger':form.errors.has('price')}">
                                                    <label class="col-md-12">Price</label>
                                                    <div class="col-md-12">
                                                        <input type="number" class="form-control" v-model="form.price" required>
                                                        <small class="form-control-feedback" v-if="form.errors.has('price')"> <strong v-text="form.errors.get('price')"></strong></small>
                                                    </div>
                                                </div>


                                        </div>




                                    </div>

                                </div>
                                <hr>
                                <div class="form-actions" style="padding-left: 15px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn btn-success" :disabled="form.errors.any()">Submit</button>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                             </form>


                        </div>
                    </div>
                    <div class="tab-pane" id="working" role="tabpanel">
                        <div class="card-body">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-success" @click="onSubmitSchedule">Submit Schedule</button>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="row">

                                <div class="col-md-5">

                                    From: <vue-timepicker v-model="from" :minute-interval="15" format="HH:mm"></vue-timepicker>
                                    To: <vue-timepicker v-model="to" :minute-interval="15" format="HH:mm"></vue-timepicker>




                                </div>
                                <div class="col-md-4">
                                    <select class="form-control custom-select" v-model="quick_day">
                                        <option selected disabled value="">Please select a day...</option>
                                        <option  value="monday">Monday</option>
                                        <option  value="tuesday">Tuesday</option>
                                        <option  value="wednesday">Wednesday</option>
                                        <option  value="thursday">Thursday</option>
                                        <option  value="friday">Friday</option>
                                        <option  value="saturday">Saturday</option>
                                        <option  value="Sunday">Sunday</option>

                                    </select>

                                </div>
                                <div class="col-md-3">

                                    <button class="btn btn-info" @click="quickSelect" :disabled="quick_day==''">Quick Select</button>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-12">

                                    <small style="color: red;" class="form-control-feedback" v-if="quick_error!=''"> <strong v-text="quick_error"></strong></small>

                                </div>

                            </div>
                            <hr>
                            <div class="row">

                                <div class="col-md-12">

                                    <table class="table color-table info-table color-bordered-table info-bordered-table table-bordered">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Monday</th>
                                            <th>Tuesday</th>
                                            <th>Wednesday</th>
                                            <th>Thursday</th>
                                            <th>Friday</th>
                                            <th>Saturday</th>
                                            <th>Sunday</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><div class="hour">Availability</div></td>
                                            <td><button class="btn " @click="toggleDay('monday')" :class="{'btn-danger':!form_days.monday_available,'btn-info':form_days.monday_available}">{{monday_text}}</button></td>
                                            <td><button class="btn " @click="toggleDay('tuesday')" :class="{'btn-danger':!form_days.tuesday_available,'btn-info':form_days.tuesday_available}">{{tuesday_text}}</button></td>
                                            <td><button class="btn " @click="toggleDay('wednesday')" :class="{'btn-danger':!form_days.wednesday_available,'btn-info':form_days.wednesday_available}">{{wednesday_text}}</button></td>
                                            <td><button class="btn " @click="toggleDay('thursday')" :class="{'btn-danger':!form_days.thursday_available,'btn-info':form_days.thursday_available}">{{thursday_text}}</button></td>
                                            <td><button class="btn " @click="toggleDay('friday')" :class="{'btn-danger':!form_days.friday_available,'btn-info':form_days.friday_available}">{{friday_text}}</button></td>
                                            <td><button class="btn " @click="toggleDay('saturday')" :class="{'btn-danger':!form_days.saturday_available,'btn-info':form_days.saturday_available}">{{saturday_text}}</button></td>
                                            <td><button class="btn " @click="toggleDay('sunday')" :class="{'btn-danger':!form_days.sunday_available,'btn-info':form_days.sunday_available}">{{sunday_text}}</button></td>
                                        </tr>
                                        <tr v-for="n in 24" v-if="increment==45 && (n-1)%3===0">

                                            <td><div class="hour">{{n-1}}:00h</div></td>
                                            <td>

                                                <span class="timeslot" v-for="n2 in slots_num"
                                                      @click="toggleSlot('monday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'monday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'monday_availability'),'inactive':!form_days.monday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>


                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('tuesday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'tuesday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'tuesday_availability'),'inactive':!form_days.tuesday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('wednesday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'wednesday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'wednesday_availability'),'inactive':!form_days.wednesday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('thursday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'thursday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'thursday_availability'),'inactive':!form_days.thursday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('friday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'friday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'friday_availability'),'inactive':!form_days.friday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('saturday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'saturday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'saturday_availability'),'inactive':!form_days.saturday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>

                                            </td>
                                            <td>
                                                <span class="timeslot" v-for="n2 in slots_num"
                                                      @click="toggleSlot('sunday',hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailability(hourFormat(n-1,increment*(n2-1)),'sunday_availability'),'unavailable':!checkAvailability(hourFormat(n-1,increment*(n2-1)),'sunday_availability'),'inactive':!form_days.sunday_available}">
                                                    {{hourFormat(n-1,increment*(n2-1))}}
                                                </span>
                                            </td>

                                        </tr>
                                        <tr v-if="increment!=45" v-for="n in 24">

                                            <td><div class="hour" >{{n-1}}:00h</div></td>
                                            <td>

                                                <span class="timeslot" v-for="n2 in slots_num"
                                                      @click="toggleSlot('monday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'monday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'monday_availability'),'inactive':!form_days.monday_available}">

                                                    {{hourPrettify((n-1),((n2-1)*increment))}}



                                                </span>


                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('tuesday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'tuesday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'tuesday_availability'),'inactive':!form_days.tuesday_available}">

                                                     {{hourPrettify((n-1),((n2-1)*increment))}}


                                                </span>

                                            </td>
                                            <td>
                                                <span class="timeslot" v-for="n2 in slots_num"
                                                      @click="toggleSlot('wednesday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'wednesday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'wednesday_availability'),'inactive':!form_days.wednesday_available}">

                                                     {{hourPrettify((n-1),((n2-1)*increment))}}


                                                </span>

                                            </td>
                                            <td>
                                                <span class="timeslot" v-for="n2 in slots_num"
                                                      @click="toggleSlot('thursday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'thursday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'thursday_availability'),'inactive':!form_days.thursday_available}">

                                                     {{hourPrettify((n-1),((n2-1)*increment))}}


                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('friday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'friday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'friday_availability'),'inactive':!form_days.friday_available}">

                                                    {{hourPrettify((n-1),((n2-1)*increment))}}


                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('saturday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'saturday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'saturday_availability'),'inactive':!form_days.saturday_available}">

                                                     {{hourPrettify((n-1),((n2-1)*increment))}}


                                                </span>

                                            </td>
                                            <td>
                                                 <span class="timeslot" v-for="n2 in slots_num"
                                                       @click="toggleSlot('sunday',hourPrettify((n-1),((n2-1)*increment)))" :class="{'timeslot-full-width':increment==60,'available':checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'sunday_availability'),'unavailable':!checkAvailability(hourPrettify((n-1),((n2-1)*increment)),'sunday_availability'),'inactive':!form_days.sunday_available}">

                                                     {{hourPrettify((n-1),((n2-1)*increment))}}


                                                </span>

                                            </td>

                                        </tr>


                                        </tbody>
                                    </table>

                                </div>

                            </div>


                        </div>
                    </div>
                    <div class="tab-pane" id="special" role="tabpanel">
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-8">

                                    <v-date-picker
                                            @input="selectDate"
                                            :attributes='attributes'
                                            is-inline
                                            is-expanded
                                            is-double-paned
                                            mode='single'
                                            :min-date="minDate"
                                            v-model='selectedDate'
                                            show-caps>

                                    </v-date-picker>
                                    <hr>
                                    <div class="row">

                                        <div class="col-md-12">
                                            <h3>Legends</h3>

                                        </div>
                                        <div class="col-md-4">

                                            <span class="selected legend"></span><p>Selected Date</p>

                                        </div>
                                        <div class="col-md-4">
                                            <span class="partial legend"></span><p>Special Day</p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="off legend"></span><p>Day Off</p>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <div class="selected-date">
                                        <h3>Selected Date:{{selectedDateFormated}}</h3>
                                        <button class="btn " @click="toggleSpecificDate()" :class="{'btn-danger':!form_specific_days.available,'btn-info':form_specific_days.available}">{{dayAvailableText}}</button>
                                        <hr>
                                        <div v-if="form_specific_days.available">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <label style="width: 50px;">From:</label>
                                                    <vue-timepicker v-model="fromDay" :minute-interval="15" format="HH:mm"></vue-timepicker>
                                                </div>
                                                <div class="col-md-12" style="margin-top:10px">
                                                   <label style="width: 50px;">To:</label>
                                                    <vue-timepicker v-model="toDay" :minute-interval="15" format="HH:mm"></vue-timepicker>
                                                </div>

                                            </div>
                                            <button style="margin-top:10px" class="btn btn-info" @click="quickSelectDay">Quick Select</button>
                                            <small style="color: red;margin-top:10px" class="form-control-feedback" v-if="quick_specificError!=''"> <strong v-text="quick_specificError"></strong></small>
                                        </div>



                                        <hr v-if="form_specific_days.available">
                                        <div class="row" v-if="increment!=45" v-for="n in 24" style="padding-left:15px;padding-right:15px;">

                                            <div :class="'col-md-'+12/slots_num" v-for="n2 in slots_num" style="padding-right:0px;padding-left:0px">

                                                 <span class="timeslot-special" @click="toggleDaySlot(hourPrettify((n-1),((n2-1)*increment)))" :class="{'available':checkAvailabilityDay(hourPrettify((n-1),((n2-1)*increment))),'unavailable':!checkAvailabilityDay(hourPrettify((n-1),((n2-1)*increment))),'inactive':!form_specific_days.available}">


                                                        {{hourPrettify((n-1),((n2-1)*increment))}}




                                                  </span>





                                            </div>


                                        </div>
                                        <div class="row" v-if="increment==45 && (n-1)%3==0" v-for="n in 24" style="padding-left:15px;padding-right:15px;">

                                            <div :class="'col-md-'+12/slots_num" v-for="n2 in slots_num" style="padding-right:0px;padding-left:0px">

                                                 <span class="timeslot-special" @click="toggleDaySlot(hourFormat(n-1,increment*(n2-1)))" :class="{'available':checkAvailabilityDay(hourFormat(n-1,increment*(n2-1))),'unavailable':!checkAvailabilityDay(hourFormat(n-1,increment*(n2-1))),'inactive':!form_specific_days.available}">


                                                 {{hourFormat(n-1,increment*(n2-1))}}


                                                  </span>





                                            </div>


                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn btn-success" @click="onSubmitSpecialSchedule">Submit Schedule</button>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>



                                </div>

                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Column -->
    </div>

</template>
<script>


    import VCalendar from 'v-calendar';
    import 'v-calendar/lib/v-calendar.min.css';
    import moment from 'moment';
    import VueTimepicker from 'vue2-timepicker';


    export default{


        data(){

            return {

                increment:15,
                slots_num:4,
                selectedDate: new Date(),
                minDate:new Date(),
                form:new Form({increment:'',price:0,event_duration:''}),
                form_days:new Form({
                    monday_available:false,tuesday_available:false,wednesday_available:false,thursday_available:false,friday_available:false,
                    saturday_available:false,sunday_available:false,monday_availability:[],tuesday_availability:[],
                    wednesday_availability:[],thursday_availability:[],friday_availability:[],saturday_availability:[],sunday_availability:[]
                }),
                form_specific_days:new Form({day: new Date(),available:true,availability:[]}),
                monday_text:'Working Day',
                tuesday_text:'Working Day',
                wednesday_text:'Working Day',
                thursday_text:'Working Day',
                friday_text:'Working Day',
                saturday_text:'Working Day',
                sunday_text:'Working Day',
                days_marker: [],
                from:{
                    HH: "00",
                    mm: "00",
                },
                to:{
                    HH: "01",
                    mm: "00",
                },
                quick_day:'',
                quick_error:'',
                fromDay:{
                    HH: "00",
                    mm: "00",
                },
                toDay:{
                    HH: "01",
                    mm: "00",
                },
                quick_specificDay:'',
                quick_specificError:''


            };
        },
        components: {VCalendar,VueTimepicker},
        mounted(){


            axios.get('/employee/get-calendar').
            then(response=>{


                this.form.increment=(response.data.increment==null) ? '' : response.data.increment;
                this.form.event_duration=(response.data.event_duration==null) ? '' : response.data.event_duration;
                this.form.price=(response.data.price==null) ? '' : response.data.price;

                this.increment=response.data.increment;
               if(this.increment!=null){

                 this.calculateSlotsNum(this.increment);
               }



            });

            this.getSchedule();

            this.getSpecialSchedule(new Date());
            this.getDaysMarkers();


        },
        computed:{



            attributes() {
                return this.days_marker.map(t => ({
                    key: `day.${t.id}`,
                    highlight: {
                        backgroundColor: t.backgroundColor,
                        borderColor:t.borderColor,
                        borderWidth:'2px',
                        borderStyle:'solid',
                        color:t.color
                    },
                    dates: moment(t.date).format(),
                    customData: t,
                }));
            },
          dayAvailableText(){

              if(this.form_specific_days.available){

                  return 'Working Day';
              }else{

                  return 'Day off';
              }

          },
          selectedDateFormated(){

              return moment(this.selectedDate).format("MMM Do YY");
          }

        },
        methods:{

            quickSelect(){



                let start_hour=this.from.HH;
                let start_minutes=this.from.mm;

                let end_hour=this.to.HH;
                let end_minutes=this.to.mm;

                let start=(parseInt(start_hour)*60)+parseInt(start_minutes);
                let end=(parseInt(end_hour)*60)+parseInt(end_minutes);

                if(end>start){

                    this.quick_error='';
                    let timeslots=[];

                    this.form_days[this.quick_day+'_availability']=[];
                    for(let i=start;i<=end;i+=15){

                        let hours=Math.trunc(i/60);
                        let minutes=(i%60==0) ? '00' : i%60;

                        if(this.form_days[this.quick_day+'_available']){

                            this.form_days[this.quick_day+'_availability'].push(hours+':'+minutes);
                        }



                    }

                }else{

                    this.quick_error='"To" time has to be greater than "From".Please change the timeframe and try again.';
                }



            },
            quickSelectDay(){



                let start_hour=this.fromDay.HH;
                let start_minutes=this.fromDay.mm;

                let end_hour=this.toDay.HH;
                let end_minutes=this.toDay.mm;

                let start=(parseInt(start_hour)*60)+parseInt(start_minutes);
                let end=(parseInt(end_hour)*60)+parseInt(end_minutes);

                if(end>start){

                    this.quick_specificError='';
                    let timeslots=[];

                    this.form_specific_days.availability=[];
                    for(let i=start;i<=end;i+=15){

                        let hours=Math.trunc(i/60);
                        let minutes=(i%60==0) ? '00' : i%60;

                        if( this.form_specific_days.available){

                            this.form_specific_days.availability.push(hours+':'+minutes);
                        }



                    }

                }else{

                    this.quick_specificError='"To" time has to be greater than "From".Please change the timeframe and try again.';
                }



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
            hourPrettify(hour,minutes){

                if(minutes==0){

                    minutes='00';
                }


                return hour+':'+minutes;


            },
            hourFormat(hour,minutes){

                let total=((hour*60)+minutes);
                let final_hours = Math.trunc(total/60);
                let final_minutes = (total % 60)==0 ? '00' : total % 60;

                return  final_hours + ':' + final_minutes;

            },
            getDaysMarkers(){

                axios.get('/employee/get-days-markers?rules=1').
                then(response=>{



                    this.days_marker=response.data.days_markers;


                });


            },
            getSchedule(){

                axios.get('/employee/get-schedule').
                then(response=>{



                    this.form_days.monday_available=parseInt(response.data.monday_available);
                    this.form_days.tuesday_available=parseInt(response.data.tuesday_available);
                    this.form_days.wednesday_available=parseInt(response.data.wednesday_available);
                    this.form_days.thursday_available=parseInt(response.data.thursday_available);
                    this.form_days.friday_available=parseInt(response.data.friday_available);
                    this.form_days.saturday_available=parseInt(response.data.saturday_available);
                    this.form_days.sunday_available=parseInt(response.data.sunday_available);

                    if(response.data.timeslots!==undefined && response.data.timeslots.length!=0){

                        response.data.timeslots.forEach((timeslot,index)=>{

                            this.form_days[timeslot.day+'_availability'].push(timeslot.timeslot);

                        });


                    }else{

                        this.form_days['monday_availability']=[];
                        this.form_days['tuesday_availability']=[];
                        this.form_days['wednesday_availability']=[];
                        this.form_days['thursday_availability']=[];
                        this.form_days['friday_availability']=[];
                        this.form_days['saturday_availability']=[];
                        this.form_days['sunday_availability']=[];

                    }


                    this.availabilityText();

                });

            },
            getSpecialSchedule(date){

                let new_date=moment(date).format('YYYY-MM-DD');
                axios.get('/employee/get-special-schedule?day='+new_date).
                then(response=>{

                    this.form_specific_days.available=true;
                    this.form_specific_days.availability=[];
                    if(response.data !==undefined && response.data.length!=0 && response.data!=""){

                        this.form_specific_days.available=response.data.available;
                        if(response.data.special_timeslots!==undefined || response.data.special_timeslots.length!=0){

                            response.data.special_timeslots.forEach((timeslot,index)=>{

                                this.form_specific_days.availability.push(timeslot.timeslot);

                            });


                        }else{

                            this.form_specific_days.available=true;
                            this.form_specific_days.availability=[];
                        }
                    }else{

                        this.form_specific_days.available=true;
                        this.form_specific_days.availability=[];
                    }





                });

            },
            selectDate(date){

                this.form_specific_days.day=date;
                this.getSpecialSchedule(date);

            },
            toggleSlot(day,time){

                if(this.form_days[day+'_available']==false){

                    return false;
                }


                let index=this.form_days[day+'_availability'].indexOf(time);
                if(index>=0){

                    this.form_days[day+'_availability'].splice(index,1);
                }else{
                    this.form_days[day+'_availability'].push(time);
                }


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
            toggleDaySlot(time){

                if(this.form_specific_days.available==false){

                    return false;
                }

                let index=this.form_specific_days.availability.indexOf(time);
                if(index>=0){

                    this.form_specific_days.availability.splice(index,1);
                }else{
                    this.form_specific_days.availability.push(time);
                }


            },
            toggleSpecificDate(){

                this.form_specific_days.available=!this.form_specific_days.available;

            },
            toggleDay(day){

                this.form_days[day+'_available']=!this.form_days[day+'_available'];
                this.availabilityText();

            },
            availabilityText(){

                if(!this.form_days.monday_available){

                    this.monday_text='Day Off';
                }else{

                    this.monday_text='Working Day';
                }
                if(!this.form_days.tuesday_available){

                    this.tuesday_text='Day Off';
                }else{

                    this.tuesday_text='Working Day';
                }
                if(!this.form_days.wednesday_available){

                    this.wednesday_text='Day Off';
                }else{

                    this.wednesday_text='Working Day';
                }
                if(!this.form_days.thursday_available){

                    this.thursday_text='Day Off';
                }else{

                    this.thursday_text='Working Day';
                }
                if(!this.form_days.friday_available){

                    this.friday_text='Day Off';
                }else{

                    this.friday_text='Working Day';
                }
                if(!this.form_days.saturday_available){

                    this.saturday_text='Day Off';
                }else{

                    this.saturday_text='Working Day';
                }
                if(!this.form_days.sunday_available){

                    this.sunday_text='Day Off';
                }else{

                    this.sunday_text='Working Day';
                }

            },
            checkAvailability(time,day){




                if(this.form_days[day]===undefined || this.form_days[day].length==0){

                    return false;
                }

                if(this.form_days[day].indexOf(time)>=0){

                    return true;
                }

                return false;

            },
            onSubmit(){

                let that=this;
                this.form.post('/employee/calendar-settings',false)
                    .then(status=>{



                        if(status.success){

                            that.increment=that.form.increment;
                            that.calculateSlotsNum(that.increment);

                            that.getSchedule();
                            that.getDaysMarkers();

                            $.toast({
                                heading: 'Rules',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 1000,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Rules',
                                text: 'Error editing  settings!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 1000,
                                stack: 6
                            });

                        }


                    });

            },
            onSubmitSchedule(){

                this.form_days.post('/employee/save-schedule',false)
                    .then(status=>{



                        if(status.success){



                            $.toast({
                                heading: 'Schedule',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 1000,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Schedule',
                                text: 'Error editing  schedule!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 1000,
                                stack: 6
                            });

                        }


                    });
            },
            onSubmitSpecialSchedule(){

                let old_date=this.form_specific_days.day;
                this.form_specific_days.day=moment(this.form_specific_days.day).format('YYYY-MM-DD');
                let that=this;
                this.form_specific_days.post('/employee/save-special-schedule',false)
                    .then(status=>{



                        if(status.success){

                            that.getDaysMarkers();

                            $.toast({
                                heading: 'Schedule',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 1000,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Schedule',
                                text: 'Error editing  schedule!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 1000,
                                stack: 6
                            });

                        }


                    });
            }

        }

    }

</script>
<style scoped>


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

    .selected{

        background: rgb(102, 179, 204);
    }

    .partial{

        background: rgb(255, 178, 43);
        color:green;
    }

    .off{

        background: yellow;
    }

</style>
