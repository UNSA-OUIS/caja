const Permissions = {};

Permissions.install = function(Vue, options){
    Vue.prototype.$permissions = {
        /**
         * [{ "name": "can create"}, {"name": "show own programs", "owner": 1}]
         * @param permissions
         * @returns {boolean}
         */
        can: (permissions = []) => {
            const user = Vue.prototype.$page.props.user;               
            
            //if (user.roles[0].name === "Administrador") return true;

            let can = false;

            permissions.forEach(p => {
                //permisos solo desde usuarios
                //const userHasPermission = user.permissions.find(_p => _p.name === p.name);
                const userOrRoleHasPermission = user.allPermissions.find(_p => _p.name === p.name);
                if (userOrRoleHasPermission) {
                    if (p.hasOwnProperty("owner")) {
                        if (p.owner === user.id) can = true;
                    } else {
                        can = true;
                    }
                }
            })

            return can;
        }
    }
}

export default Permissions;
