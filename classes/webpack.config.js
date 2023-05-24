'use strict';

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');
const glob = require('glob');

const entryCSS = {
    section_reliable_partner: './assets/scss/blocks/home/_section-reliable-partner.scss',
    section_integrated_approach: './assets/scss/blocks/home/_section-integrated-approach.scss',
    what_we_do: './assets/scss/blocks/_what-we-do.scss',
    promoting_offer: './assets/scss/blocks/_promoting-offer.scss',
    robot_text: './assets/scss/blocks/_robot-text.scss',
    section_do_you_have_questions: './assets/scss/sections/_section-do-you-have-questions.scss',
    thanks_main: './assets/scss/blocks/_thanks-main.scss',
    service_about: './assets/scss/blocks/_service-about.scss',
    service_getting: './assets/scss/blocks/_service-getting.scss',
    service_form_info: './assets/scss/blocks/_service-form-info.scss',
    service_why_you_need: './assets/scss/blocks/_service-why-you-need.scss',
    banner_form: './assets/scss/blocks/_banner-form.scss',
    service_topics: './assets/scss/blocks/_service-topics.scss',
    separator: './assets/scss/blocks/_separator.scss',
    service_form_offer: './assets/scss/blocks/_service-form-offer.scss',
    service_find_out_cost: './assets/scss/blocks/_service-find-out-cost.scss',
    page_404: './assets/scss/pages/_404.scss',
    contact_page_form: './assets/scss/blocks/_contact-page-form.scss',
    numbers: './assets/scss/blocks/_numbers.scss',
    our_mission: './assets/scss/blocks/_our-mission.scss',
    benefits_of_working: './assets/scss/blocks/_benefits-of-working.scss',
    achievements: './assets/scss/blocks/_achievements.scss',
    quote: './assets/scss/blocks/_quote.scss',
    in_media: './assets/scss/blocks/_in-media.scss',
};

const entryJS = {
    main: './assets/js/main.js',
    section_main: './assets/js/blocks/section-main.js',
    section_main_default: './assets/js/blocks/section-main-default.js',
    section_main_contact: './assets/js/blocks/section-main-contact.js',
    clients: './assets/js/blocks/clients.js',
    get_forecast: './assets/js/blocks/get_forecast.js',
    industry: './assets/js/blocks/industry.js',
    why_us: './assets/scss/blocks/_why-us.scss',
    goals: './assets/scss/blocks/_goals.scss',
    progress_of_work: './assets/scss/blocks/_progress_of_work.scss',
    results_info: './assets/scss/blocks/_results_info.scss',
    customer_review: './assets/scss/blocks/_customer_review.scss',
    our_approach: './assets/js/blocks/our_approach.js',
    review: './assets/js/blocks/review.js',
    results: './assets/js/blocks/results.js',
    faq: './assets/scss/blocks/_faq.scss',
    contact: './assets/js/blocks/contact.js',
    blog: './assets/js/pages/blog.js',
    article: './assets/js/pages/article.js',
    article_rating: './assets/js/modules/article_rating.js',
    comments: './assets/js/modules/comments.js',
    service_introduction: './assets/js/blocks/service-introduction.js',
    service_how_we_work: './assets/js/blocks/service-how-we-work.js',
    service_industry: './assets/js/blocks/service-industry.js',
    service_stages: './assets/js/blocks/service-stages.js',
    tariffs: './assets/js/blocks/tariffs.js',
    related_cases: './assets/js/blocks/related_cases.js',
    cases: './assets/js/blocks/cases.js',
    block_blog: './assets/js/blocks/blog.js',
    about_company: './assets/js/blocks/about-company.js',
    our_values: './assets/js/blocks/our-values.js',
    team: './assets/js/blocks/team.js',
    video_reviews: './assets/js/blocks/video-reviews.js',
};

const entry = Object.assign({}, entryCSS, entryJS);

module.exports = {
    mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
    watch: true,

    entry: entry,

    output: {
        path: path.resolve(__dirname, 'assets/dist'),
        filename: '[name].min.js'
    },

    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader',
                ]
            }
        ]
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].min.css',
            runtime: false
        }),
        {
            apply: (compiler) => {
                compiler.hooks.emit.tap('RemoveUnusedJSFiles', (compilation) => {
                    Object.keys(entryCSS).forEach((name) => {
                        const fileName = `${name}.min.js`;
                        if (compilation.assets[fileName]) {
                            delete compilation.assets[fileName];
                        }
                    });
                });
            },
        }
    ],

    stats: {
        preset: 'minimal',
        moduleTrace: true,
        version: false,
    },

    externals: {
        jquery: 'jQuery'
    },

    optimization: {
        minimize: true,
        usedExports: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin({
                parallel: true,
                terserOptions: {
                    compress: {
                        drop_console: false,
                    },
                },
            }),
        ],
    },

    devtool: false
};