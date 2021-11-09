module.exports = {
    root: true,
    extends: ['eslint:recommended', 'plugin:vue/essential', 'prettier'],
    env: {
        browser: true,
        es6: true,
        node: true,
    },
    parserOptions: {
        parser: '@babel/eslint-parser',
        sourceType: 'module',
        ecmaVersion: 2020,
        ecmaFeatures: {
            jsx: true,
        },
    },
    plugins: ['babel', 'prettier', 'promise'],
    rules: {
        'comma-dangle': ['error', 'always-multiline'],
        'no-console':
            process.env.NODE_ENV === 'production' ? ['error', { allow: ['warn', 'error'] }] : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        quotes: ['error', 'single', { avoidEscape: true }],
        'vue/component-tags-order': [
            'error',
            {
                order: ['template', 'script', 'style'],
            },
        ],
        'vue/no-template-target-blank': [
            'error',
            {
                allowReferrer: false,
                enforceDynamicLinks: 'always',
            },
        ],
    },
    globals: {
        _: 'readable',
        axios: 'readable',
        moment: 'readable',
        route: 'readable',
        Twitch: 'readable',
        twitchAPI: 'readable',
        Vue: 'readable',
    },
}
