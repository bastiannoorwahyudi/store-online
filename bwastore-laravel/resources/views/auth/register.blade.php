@extends('layouts.auth')

@section('content')

    <div class="page-content page-auth" id="register">
        <div class="section-store-auth" data-aos="fade-up">
            <div class="row align-items-center row-login justify-content-center">
                <div class="col-lg-4">
                    <h2>
                        Memulai untuk jual beli <br>
                        dengan cara terbaru
                    </h2>
                    <form method="POST" action="{{ route('register') }}" class="mt-3">
                        @csrf
                        <div class="form-group">
                            <label for="">Full Name</label>
                            <input
                                v-model="name"
                                id="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus>
                                
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Email Address</label>
                            <input id="email" @change="checkForEmailAvailability()" type="email" v-model="email" class="form-control @error('email') is-invalid @enderror" :class="{'is-invalid' : this.email_unavailable}" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Password Confirm</label>
                            <input id="password-confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Store</label>
                            <p class="text-muted">
                                Apakah anda juga ingin membuka toko?
                            </p>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" name="is_store_open" id="openStoreTrue" v-model="is_store_open" :value="true">
                                <label for="openStoreTrue" class="custom-control-label">
                                    Iya, boleh
                                </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" name="is_store_open" id="openStoreFalse" v-model="is_store_open" :value="false">
                                <label for="openStoreFalse" class="custom-control-label">
                                    Enggak, Terima Kasih
                                </label>
                            </div>
                        </div>
                        <div class="form-group" v-if="is_store_open">
                            <label for="">Nama Toko</label>
                            <input type="text" v-model="store_name" id="store_name" name="store_name" class="form-control @error('password') is-invalid @enderror" required autocomplete autofocus>
                            @error('store_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="form-group" v-if="is_store_open">
                            <label for="">Kategori</label>
                            <select name="categories_id" class="form-control">
                                <option value="" disable> -- Select Category -- </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-4" :disabled="this.email_unavailable">
                            Sign Up Now
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-signup btn-block mt-2">
                            Back to Sign In
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <!-- inisialisasi VUE JS -->
    <script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        Vue.use(Toasted);

        var register = new Vue({
            el: '#register',
            mounted() {
                AOS.init();
            },
            methods: {
                checkForEmailAvailability: function() {
                    var self = this;
                    axios.get('{{ route('api-register-check') }}', {
                        params: {
                            email: this.email
                        }
                    })
                        .then(function (response) {
                            // jika respon data Available, email tersedia, jika tidak Email sidah terdaftar
                            if(response.data == 'Available') {
                                self.$toasted.show(
                                    "Email anda tersedia..!, Silahkan lanjutkan", 
                                    {
                                    position: "top-center",
                                    className: "rounded",
                                    duration: 1000
                                    }
                                );
                                self.email_unavailable = false;
                            } else {
                                self.$toasted.error(
                                    "Maaf, email sudah terdaftar pada sistem kami.", 
                                    {
                                    position: "top-center",
                                    className: "rounded",
                                    duration: 1000
                                    }
                                );
                                self.email_unavailable = true;
                            }
                            console.log(response);
                        });
                }
            },
            data() {
                return {
                    name: "Angga Hazza Sett",
                    email: "kamujagoan@bwa.id",
                    is_store_open: true,
                    store_name :"",
                    email_unavailable :false,
                }
            },
        });
    </script>
    <!-- End inisialisasi VUE JS -->
@endpush
