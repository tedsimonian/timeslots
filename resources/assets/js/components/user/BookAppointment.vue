<template>


    <div class="row">


        <div class="col-md-12">

            <div class="card">

                <div class="card-body">



                    <form-wizard finish-button-text="Pay Now" ref="wizard" @on-complete="onComplete" color="#1976d2" title="Book an Appointment" :subtitle="calendarInfo">
                        <tab-content :title="empNameTitle"
                                     icon="ti-user" :before-change="validateEmployee">

                            <div class="container">

                                <div class="row">


                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">

                                        <model-list-select :list="employees"
                                                           option-value="id"
                                                           option-text="name"
                                                           v-model="booking.employee"
                                                           placeholder="Find an employee..."
                                                           @searchchange="searchEmployee">
                                        </model-list-select>

                                    </div>

                                </div>
                            </div>


                        </tab-content>
                        <tab-content :title="dateTitle"
                                     icon="ti-calendar" :before-change="validateDate">
                            <v-date-picker

                                    is-inline
                                    is-expanded
                                    is-double-paned
                                    mode='single'
                                    @input="selectDate"
                                    :attributes='attributes'
                                    :min-date="minDate"
                                    v-model='selectedDate'
                                    :disabled-dates='disabled_dates'
                                    :available-dates='available_days'
                                    show-caps>

                            </v-date-picker>
                        </tab-content>
                        <tab-content :title="timeslotTitle"
                                     icon="ti-time" :before-change="validateTimeslot">
                            <div class="row"  v-if="availability.length!=0" >

                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-md-3"  style="margin-top: 10px;" v-for="n in availability">

                                                 <span @click="toggleDaySlot(n)" class="timeslot-special" :class="{'available':checkSelected(n),'unavailable':checkAvailabilityDay(n)}">


                                                    {{n}}


                                                  </span>
                                        </div>

                                    </div>


                                </div>





                            </div>
                        </tab-content>
                        <tab-content title="Payment"
                                     icon="ti-check">
                            <div class="row">
                                <div class="col-md-3">


                                </div>
                                <div class="col-md-6">
                                    <p v-if="verified==1 && can_pay">You have requested to book an appointment with <strong>{{booking.employee.name}}</strong>:<br>
                                        <strong>Date: {{dateFormat}}</strong><br>
                                        <strong>Time:</strong> {{booking.timeslot}} - {{endTime(booking.timeslot,booking.duration)}}<br>
                                        <strong>Price:</strong> ${{booking.price}}<br>
                                        <strong>Duration:</strong> {{booking.duration}} min</p>

                                    <p v-if="verified!=1">
                                        <strong>Please verify your account in order to book appointments!</strong>

                                    </p>
                                    <p v-if="!can_pay">
                                        <strong>You dont have the permission to proceed with the payment!</strong>

                                    </p>

                                </div>
                            </div>

                        </tab-content>
                        <template slot="footer" slot-scope="props">
                            <div class=wizard-footer-left>
                                <wizard-button v-if="props.activeTabIndex > 0" @click.native="props.prevTab()" :style="props.fillButtonStyle">Back</wizard-button>
                            </div>
                            <div class="wizard-footer-right">
                                <wizard-button v-if="!props.isLastStep" @click.native="props.nextTab()" class="wizard-footer-right" :style="props.fillButtonStyle">Next</wizard-button>


                                <wizard-button v-if="props.isLastStep && verified==1 && can_pay" @click.native="checkout" class="wizard-footer-right finish-button" :style="props.fillButtonStyle">Pay Now</wizard-button>
                                <wizard-button v-if="props.isLastStep && verified==1 && can_pay" @click.native="payLater" class="wizard-footer-right " :style="props.fillButtonStyle" style="margin-right: 20px;">Pay Later</wizard-button>

                            </div>

                        </template>
                    </form-wizard>


                </div>

            </div>



        </div>



    </div>






</template>
<script>

    import VCalendar from 'v-calendar';
    import 'v-calendar/lib/v-calendar.min.css';
    import moment from 'moment';
    import { ModelListSelect } from 'vue-search-select';
    import {FormWizard, TabContent} from 'vue-form-wizard'
    import 'vue-form-wizard/dist/vue-form-wizard.min.css'
    import VueStripeCheckout from 'vue-stripe-checkout';


    const options = {
        name: 'Payment Process',
        key:'pk_test_a1ce7hnnL7ysRshCjsgjY26J',
        currency: 'CAD',
        image: 'https://blog.apruve.com/hs-fs/hubfs/Payment%20Delays-01.png?width=100&name=Payment%20Delays-01.png',
        locale: 'auto',
        billingAddress: false,
        panelLabel: 'Pay Now {{amount}}',
    };

    Vue.use(VueStripeCheckout,options);


    export default{


        data(){

            return {

                selectedDate: null,
                minDate:new Date(),
                employees:[],
                disabled_dates:[],
                available_days:[],
                booking:new Form({employee:{},date:'',timeslot:'',price:"",duration:'',increment:''}),
                days_marker:[],
                availability:[],
                hour_availability:[],
                selected:[],
                verified:false,
                can_pay:true


            };
        },
        props:['stripeKey'],
        components: {VCalendar,ModelListSelect,FormWizard,TabContent,VueStripeCheckout},
        computed:{



            empNameTitle(){

                if(!this.isEmpty(this.booking.employee)){

                    return 'Choose Employee ( '+this.booking.employee.name+' )';
                }
                return 'Choose Employee';

            },
            dateTitle(){

                if(this.booking.date!=''){

                    return 'Choose Date ( '+ moment(this.booking.date).format("YYYY-MM-DD")+' )';
                }
                return 'Choose Date';

            },
            dateFormat(){

                return moment(this.booking.date).format("YYYY-MM-DD");
            },
            timeslotTitle(){

                if(this.booking.timeslot!=''){

                    return 'Choose a Timeslot ( '+ this.booking.timeslot+' - '+this.endTime(this.booking.timeslot,this.booking.duration)+' )';
                }
                return 'Choose a Timeslot';

            },
            calendarInfo(){

                return 'Price: $' + this.booking.price + '       Duration: '+ this.booking.duration +' min';

            },
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
                    popover:{

                        label:'Fully Booked',
                    },
                    dates: t.date,
                    customData: t,
                }));

                return attributes;

            },

        },
        mounted(){

            this.checkPermission();
            this.searchEmployee('');
            this.isVerified();

        },
        methods:{

            checkPermission(){

                axios.get('/user/check-permission').
                then(response=>{

                    this.can_pay=response.data.message;

                });

            },
            isVerified(){

                axios.get('/user/is-verified').
                then(response=>{

                    this.verified=response.data;

                });
            },
            resetWizard(){

                this.disabled_dates=[];
                this.available_days=[];
                this.booking=new Form({employee:{},date:'',timeslot:'',price:"",duration:'',increment:''});
                this.days_marker=[];
                this.availability=[];
                this.hour_availability=[];
                this.selected=[];
                this.$refs.wizard.reset();



            },
            payLater(){


                axios.post('/user/complete-event',{

                    later:true,
                    amount:this.booking.price,
                    employee_id:this.booking.employee.id,
                    day:  moment(this.booking.date).format('YYYY-MM-DD'),
                    timeslot:this.booking.timeslot,
                    duration:this.booking.duration
                }).
                then(response=>{



                    if(response.data.success){

                        this.resetWizard();
                        $.toast({
                            heading: 'Appointment',
                            text: response.data.message,
                            position: 'top-center',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 1000,
                            stack: 6
                        });


                    }else{

                        $.toast({
                            heading: 'Appointment',
                            text: 'Error booking an Appointment!',
                            position: 'top-center',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 1000,
                            stack: 6
                        });
                    }



                });


            },
            checkout() {

                this.$checkout.open({
                    amount: this.booking.price*100,
                    token: (token) => {

                        axios.post('/user/complete-event',{

                            later:false,
                            amount:this.booking.price*100,
                            token:token,
                            employee_id:this.booking.employee.id,
                            day:  moment(this.booking.date).format('YYYY-MM-DD'),
                            timeslot:this.booking.timeslot,
                            duration:this.booking.duration
                        }).
                        then(response=>{



                            if(response.data.success){

                                this.resetWizard();
                                $.toast({
                                    heading: 'Appointment',
                                    text: response.data.message,
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 1000,
                                    stack: 6
                                });


                            }else{

                                $.toast({
                                    heading: 'Appointment',
                                    text: 'Error booking an Appointment!',
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 1000,
                                    stack: 6
                                });
                            }



                        });
                    }
                });
            },
            isLastStep() {

                if (this.$refs.wizard) {

                    return this.$refs.wizard.isLastStep
                }
                return false
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
            toggleDaySlot(time){


                    let index=this.selected.indexOf(time);
                    if(index>=0){

                        this.selected.splice(index,1);

                    }else{

                        this.selected=[];

                        this.booking.timeslot=time;
                        this.selected.push(time);
                    }

            },
            checkSelected(time){

                if(this.selected===undefined || this.selected.length==0){

                    return false;
                }

                if(this.selected.indexOf(time)>=0){

                    return true;
                }

                return false;
            },
            checkAvailabilityDay(time){



                if(this.availability===undefined || this.availability.length==0){

                    return false;
                }

                if(this.availability.indexOf(time)>=0){

                    return true;
                }

                return false;


            },
            getCalendarInfo(employee_id){


                axios.get('/user/get-calendar-info/'+employee_id).
                then(response=>{


                    this.booking.price=response.data.price;
                    this.booking.duration=response.data.event_duration;
                    this.booking.increment=response.data.increment;

                    this.getFullBookings(this.booking.employee.id);

                });


            },
            getFullBookings(employee_id){


                let today=moment().format('YYYY-MM-DD H:mm');
                axios.get('/user/get-full-bookings?id='+employee_id+'&increment='+this.booking.increment+'&event_duration='+this.booking.duration+'&today='+today).
                then(response=>{


                    this.days_marker=response.data.days_markers;


                    if(response.data.days_markers.length>0){

                        for(let i=0;i<response.data.days_markers.length;i++){

                            this.disabled_dates.push({start:response.data.days_markers[i].date,end:response.data.days_markers[i].date});
                        }

                    }


                });

            },
            getAvailableTimeslots(date,employee_id){

                let new_date=moment(date).format('YYYY-MM-DD');
                let weekday=moment(new_date).weekday();
                if(weekday==0){

                    weekday=moment().isoWeekday();

                }
                let today=moment().format('YYYY-MM-DD H:mm');
                axios.get('/user/get-timeslots?id='+employee_id+'&day='+new_date+'&weekday='+weekday+'&increment='+this.booking.increment+'&event_duration='+this.booking.duration+'&today='+today).
                then(response=>{


                    if(response.data !==undefined && response.data.availability.length!=0 && response.data.availability!=""){



                        this.availability=response.data.availability;
                        this.hour_availability=response.data.hours;



                    }else{

                        this.hour_availability=[];
                        this.availability=[];
                    }


                });
            },
            getCalendarAvailability(employee_id){


                this.disabled_dates=[];
                this.available_days=[];
                axios.get('/user/get-calendar-availability/'+employee_id).
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
            selectDate(date){


              this.booking.date=date;


            },
            isEmpty(obj) {
                for(var prop in obj) {
                    if(obj.hasOwnProperty(prop))
                        return false;
                }

                return true;
             },
            validateEmployee(){

                if(!this.isEmpty(this.booking.employee)){

                    this.getCalendarInfo(this.booking.employee.id);
                    this.getCalendarAvailability(this.booking.employee.id);

                    return true;
                }

                return false;

            },
            validateDate(){

                if(this.booking.date!=''){


                    this.getAvailableTimeslots(this.booking.date,this.booking.employee.id);
                    return true;
                }

                return false;

            },
            validateTimeslot(){

                if(this.booking.timeslot!=''){



                    return true;
                }

                return false;

            },
            searchEmployee (searchText) {
                this.searchText = searchText;
                axios.get('/user/get-employees').
                then(response=>{

                    this.employees=response.data;

                });
            },
            onComplete(){
                alert('Yay. Done!');
            }
        }
    }


</script>
<style>

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

    .finish-button{
        background-color:#43A047 !important;
        border-color: #43A047 !important;
    }

</style>
