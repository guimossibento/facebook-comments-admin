export default [
    {path: '/dashboard', component: require('./components/Dashboard.vue').default},
    {path: '/profile', component: require('./components/Profile.vue').default},
    {path: '/developer', component: require('./components/Developer.vue').default},
    {path: '/users', component: require('./components/Users.vue').default},
    {
        path: '/comments/:nicheId',
        component: require('./components/Comments.vue').default,
        props: true
    },
    {
        path: '/comments',
        component: require('./components/Comments.vue').default
    },
    {path: '/niches', component: require('./components/Niches.vue').default},
    {path: '/niches-facebook-accounts', component: require('./components/NicheFacebookAccount.vue').default},
    {path: '/facebook-accounts', component: require('./components/FacebookAccounts.vue').default},
    {path: '/comment-logs', component: require('./components/CommentLog.vue').default},
    {path: '*', component: require('./components/NotFound.vue').default}
];
