<template>
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a
                        :href="route('dashboard')"
                        class="mb-4 d-block auth-logo"
                    >
                        <img
                            src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png"
                            alt=""
                            height="60"
                            class="logo logo-dark"
                        />
                        <img
                            src="https://cdn.jsdelivr.net/gh/unsa-cdn/static/siscaja/siscaja_blanco.png"
                            alt=""
                            height="60"
                            class="logo logo-light"
                        />
                    </a>
                </div>
                <button
                    type="button"
                    class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn"
                >
                    <i class="fa fa-fw fa-bars"></i>
                </button>

                <!-- App Search-->
                <form class="app-search d-none d-lg-block">
                    <div class="position-relative">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Buscar..."
                        />
                        <span class="uil-search"></span>
                    </div>
                </form>
            </div>
            <div class="d-flex">
                <div class="dropdown d-inline-block d-lg-none ml-2">
                    <button
                        type="button"
                        class="btn header-item noti-icon waves-effect"
                        id="page-header-search-dropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="uil-search"></i>
                    </button>
                    <div
                        class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                        aria-labelledby="page-header-search-dropdown"
                    >
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Buscar..."
                                        aria-label="Recipient's username"
                                    />
                                    <div class="input-group-append">
                                        <button
                                            class="btn btn-primary"
                                            type="submit"
                                        >
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dropdown d-inline-block language-switch">
                    <button
                        type="button"
                        class="btn header-item waves-effect"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <img
                            :src="`${app_url}/assets/images/flags/spain.jpg`"
                            alt="Header Language"
                            height="16"
                        />
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item">
                            <img
                                :src="`${app_url}/assets/images/flags/us.jpg`"
                                alt="user-image"
                                class="mr-1"
                                height="12"
                            />
                            <span class="align-middle">English</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item">
                            <img
                                :src="
                                    `${app_url}/assets/images/flags/spain.jpg`
                                "
                                alt="user-image"
                                class="mr-1"
                                height="12"
                            />
                            <span class="align-middle">Spanish</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item">
                            <img
                                :src="
                                    `${app_url}/assets/images/flags/germany.jpg`
                                "
                                alt="user-image"
                                class="mr-1"
                                height="12"
                            />
                            <span class="align-middle">German</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item">
                            <img
                                :src="
                                    `${app_url}/assets/images/flags/italy.jpg`
                                "
                                alt="user-image"
                                class="mr-1"
                                height="12"
                            />
                            <span class="align-middle">Italian</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item">
                            <img
                                :src="
                                    `${app_url}/assets/images/flags/russia.jpg`
                                "
                                alt="user-image"
                                class="mr-1"
                                height="12"
                            />
                            <span class="align-middle">Russian</span>
                        </a>
                    </div>
                </div>

                <div class="dropdown d-none d-lg-inline-block ml-1">
                    <button
                        type="button"
                        class="btn header-item noti-icon waves-effect"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="uil-apps"></i>
                    </button>
                    <div
                        class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                    >
                        <div class="px-lg-2">
                            <div class="row no-gutters">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img
                                            :src="
                                                `${app_url}/assets/images/brands/github.png`
                                            "
                                            alt="Github"
                                        />
                                        <span>GitHub</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img
                                            :src="
                                                `${app_url}/assets/images/brands/bitbucket.png`
                                            "
                                            alt="bitbucket"
                                        />
                                        <span>Bitbucket</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img
                                            :src="
                                                `${app_url}/assets/images/brands/dribbble.png`
                                            "
                                            alt="dribbble"
                                        />
                                        <span>Dribbble</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img
                                            :src="
                                                `${app_url}/assets/images/brands/dropbox.png`
                                            "
                                            alt="dropbox"
                                        />
                                        <span>Dropbox</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img
                                            :src="
                                                `${app_url}/assets/images/brands/mail_chimp.png`
                                            "
                                            alt="mail_chimp"
                                        />
                                        <span>Mail Chimp</span>
                                    </a>
                                </div>
                                <div class="col">
                                    <a class="dropdown-icon-item" href="#">
                                        <img
                                            :src="
                                                `${app_url}/assets/images/brands/slack.png`
                                            "
                                            alt="slack"
                                        />
                                        <span>Slack</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown d-none d-lg-inline-block ml-1">
                    <button
                        type="button"
                        class="btn header-item noti-icon waves-effect"
                        data-toggle="fullscreen"
                    >
                        <i class="uil-minus-path"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block">
                    <button
                        type="button"
                        @click="mostrarNotificaciones"
                        class="btn header-item noti-icon waves-effect"
                        id="page-header-notifications-dropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="uil-bell"></i>
                        <span class="badge badge-danger badge-pill">5</span>
                    </button>
                    <div
                        class="dropdown-menu dropdown-menu-right show"
                        v-if="menuNotificaciones"
                    >
                        <!-- item-->
                        <inertia-link
                            class="dropdown-item"
                            :href="route('sunat.tablero')"
                        >
                            <i
                                class="uil uil-user-circle font-size-18 align-middle text-muted mr-1"
                            ></i>
                            <span class="align-middle">Tablero</span>
                        </inertia-link>
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button
                        type="button"
                        @click="mostrarMenuUsuario"
                        class="btn header-item waves-effect"
                        id="page-header-user-dropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <img
                            class="rounded-circle header-profile-user"
                            :src="$page.props.user.profile_photo_path"
                            alt="Header Avatar"
                        />
                        <span
                            class="d-none d-xl-inline-block ml-1 font-weight-medium font-size-15"
                            >{{ $page.props.user.name }}</span
                        >
                        <i
                            class="uil-angle-down d-none d-xl-inline-block font-size-15"
                        ></i>
                    </button>
                    <div
                        class="dropdown-menu dropdown-menu-right show"
                        v-if="menuUsuario"
                    >
                        <!-- item-->
                        <inertia-link
                            class="dropdown-item"
                            :href="route('usuarios.perfil')"
                        >
                            <i
                                class="uil uil-user-circle font-size-18 align-middle text-muted mr-1"
                            ></i>
                            <span class="align-middle">Perfil de usuario</span>
                        </inertia-link>
                        <a class="dropdown-item" href="#"
                            ><i
                                class="uil uil-wallet font-size-18 align-middle mr-1 text-muted"
                            ></i>
                            <span class="align-middle">My_Wallet</span></a
                        >
                        <a class="dropdown-item d-block" href="#"
                            ><i
                                class="uil uil-cog font-size-18 align-middle mr-1 text-muted"
                            ></i>
                            <span class="align-middle">Settings</span>
                            <span
                                class="badge badge-soft-success badge-pill mt-1 ml-2"
                                >03</span
                            ></a
                        >
                        <a class="dropdown-item" href="#"
                            ><i
                                class="uil uil-lock-alt font-size-18 align-middle mr-1 text-muted"
                            ></i>
                            <span class="align-middle">Lock_screen</span></a
                        >
                        <a type="button" class="dropdown-item" @click="logout"
                            ><i
                                class="uil uil-sign-out-alt font-size-18 align-middle mr-1 text-muted"
                            ></i>
                            <span class="align-middle">Cerrar sesión</span></a
                        >
                    </div>
                </div>

                <div class="dropdown d-inline-block">
                    <button
                        type="button"
                        class="btn header-item noti-icon right-bar-toggle waves-effect"
                    >
                        <i class="uil-cog"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>
<script>
export default {
    data() {
        return {
            app_url: this.$root.app_url,
            menuUsuario: false,
            menuNotificaciones: false
        };
    },
    methods: {
        mostrarMenuUsuario() {
            this.menuUsuario = !this.menuUsuario;
        },
        mostrarNotificaciones() {
            this.menuNotificaciones = !this.menuNotificaciones;
        },
        logout() {
            this.$inertia.post(route("logout"));
        }
    }
};
</script>
