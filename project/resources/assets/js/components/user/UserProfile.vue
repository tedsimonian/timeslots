<template>


    <form id="update-user" class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">

        <div class="form-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('first_name')}">
                        <label class="col-md-12">First Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="name" v-model="form.first_name" required>
                            <small class="form-control-feedback" v-if="form.errors.has('first_name')"> <strong v-text="form.errors.get('first_name')"></strong></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('last_name')}">
                        <label class="col-md-12">Last Name</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="name" v-model="form.last_name" required>
                            <small class="form-control-feedback" v-if="form.errors.has('last_name')"> <strong v-text="form.errors.get('last_name')"></strong></small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group" :class="{'has-danger':form.errors.has('email')}">
                        <label class="col-md-12">Email</label>
                        <div class="col-md-12">
                            <input type="email" class="form-control" name="email" v-model="form.email" required>
                            <small class="form-control-feedback" v-if="form.errors.has('email')"> <strong v-text="form.errors.get('email')"></strong></small>
                        </div>
                    </div>
                </div>


            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('password')}">
                        <label class="col-md-12">Password</label>
                        <div class="col-md-12">
                            <input type="password" class="form-control" name="password" v-model="form.password" >
                            <small class="form-control-feedback" v-if="form.errors.has('password')"> <strong v-text="form.errors.get('password')"></strong></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('password_confirmation')}">
                        <label class="col-md-12">Password Confirm</label>
                        <div class="col-md-12">
                            <input type="password" class="form-control" name="password_confirmation" v-model="form.password_confirmation" >
                            <small class="form-control-feedback" v-if="form.errors.has('password_confirmation')"> <strong v-text="form.errors.get('password_confirmation')"></strong></small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('address')}">
                        <label class="col-md-12">Address</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="address" v-model="form.address" >
                            <small class="form-control-feedback" v-if="form.errors.has('address')"> <strong v-text="form.errors.get('address')"></strong></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('city')}">
                        <label class="col-md-12">City</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="city" v-model="form.city" >
                            <small class="form-control-feedback" v-if="form.errors.has('city')"> <strong v-text="form.errors.get('city')"></strong></small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('state')}">
                        <label class="col-md-12">State</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="state" v-model="form.state" >
                            <small class="form-control-feedback" v-if="form.errors.has('state')"> <strong v-text="form.errors.get('state')"></strong></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group" :class="{'has-danger':form.errors.has('postal_code')}">
                        <label class="col-md-12">Postal Code</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="postal_code" v-model="form.postal_code" >
                            <small class="form-control-feedback" v-if="form.errors.has('postal_code')"> <strong v-text="form.errors.get('postal_code')"></strong></small>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">

                <div class="col-md-12">

                    <div class="form-group" :class="{'has-danger':form.errors.has('notifications')}">
                        <label class="col-md-12">Notifications</label>
                        <div class="col-md-12">
                            <div class="checkbox checkbox-success">
                                <input id="notifications" type="checkbox" name="notifications" v-model="form.notifications">
                                <label for="notifications"></label>
                            </div>
                            <small class="form-control-feedback" v-if="form.errors.has('notifications')"> <strong v-text="form.errors.get('notifications')"></strong></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">

                    <div class="form-group" :class="{'has-danger':form.errors.has('gcalendar')}">
                        <label class="col-md-12" ><span v-if="!gcalendar_auth">*You must first authenticate Google Calendar</span><br>Google Calendar Integration(<span v-if="gcalendar_auth">Authenticated</span><span v-else>Unauthenticated</span>)</label>
                        <form style="margin-top:10px;margin-bottom:10px" class="col-md-12" v-if="!gcalendar_auth" method="GET" action="/auth/calendar" ><button class="btn btn-info" type="submit">Authenticate</button></form>
                        <div class="col-md-12">
                            <div class="checkbox checkbox-success">
                                <input id="gcalendar" type="checkbox" name="gcalendar" v-model="form.gcalendar">
                                <label for="gcalendar"></label>
                            </div>
                            <small class="form-control-feedback" v-if="form.errors.has('gcalendar')"> <strong v-text="form.errors.get('gcalendar')"></strong></small>
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
                            <button type="submit" class="btn btn-success" :disabled="form.errors.any()">Update</button>

                        </div>
                    </div>
                </div>

            </div>
        </div>



    </form>



</template>
<script>




    export default{


        data(){

            return {


                form:new Form({first_name:'',last_name:'',email:'',password:'',password_confirmation:'',address:'',city:'',state:'',postal_code:'',notifications:false,gcalendar:false}),
                user_id:this.id,
                gcalendar_auth:false

            };
        },
        props:['id'],
        mounted(){


            axios.get('/user/get-user/'+this.user_id).
            then(response=>{


                this.form.first_name=response.data.first_name;
                this.form.last_name=response.data.last_name;
                this.form.email=response.data.email;

                if(response.data.profile!=null){

                    this.form.address=response.data.profile.address;
                    this.form.city=response.data.profile.city;
                    this.form.state=response.data.profile.state;
                    this.form.postal_code=response.data.profile.postal_code;
                    this.form.notifications=response.data.profile.notifications;
                    this.form.gcalendar=response.data.profile.gcalendar;
                    this.gcalendar_auth=response.data.gcalendar_auth;
                }


            });



        },
        methods:{


            onSubmit(){


                this.form.patch('/user/update-profile/'+this.user_id,false)
                    .then(status=>{



                        if(status.success){

                            $.toast({
                                heading: 'Profile',
                                text: status.message,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }else{

                            $.toast({
                                heading: 'Profile',
                                text: 'Error editing  profile!',
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 4500,
                                stack: 6
                            });

                        }


                    });

            }

        }

    }

</script>