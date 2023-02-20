<template>
    <div class="fullpage-container">
        <a class="layout-logo" href="/">
            <img src="images/ethicodex.jpg" alt="Ethicodex Logo" />
        </a>
        <div class="layout-header">
            <h1>The Early History of the Codex</h1>
            <h2>Database of Early Codices</h2>
        </div>

        <div class="layout-user">
            <span v-if="loggedin">
                Username: {{ name }}, {{ role ? role.name : "" }}
            </span>
            <span v-else> Not logged in. </span>
        </div>

        <nav class="layout-navigation">
            <ul>
                <li
                    :class="{
                        lo_active: pagename == '/',
                    }"
                >
                    <Link href="/">Home</Link>
                </li>

                <li :class="{ lo_active: pagename.includes('cod') }">
                    <Link href="/codices">Codices</Link>
                </li>

                <li
                    v-if="editor"
                    :class="{
                        lo_active:
                            pagename.includes('purch') &&
                            !pagename.includes('part'),
                    }"
                >
                    <Link href="/purchases">Transactions</Link>
                </li>

                <li
                    v-if="editor"
                    :class="{ lo_active: pagename.includes('part') }"
                >
                    <Link href="/purchase_parties">Transaction Parties</Link>
                </li>
                <!--        
        <li 
          v-if="editor" 
          :class="{active: pagename.includes('modern')"
        >
          <Link href="/modern_collections">Modern Collections</Link>
        </li>                 
-->
                <li
                    v-if="!loggedin"
                    :class="{ lo_active: pagename == '/user_register' }"
                    style="float: right"
                >
                    <Link href="/user_register">Register</Link>
                </li>

                <li
                    v-if="!loggedin"
                    :class="{ lo_active: pagename == '/login' }"
                    style="float: right"
                >
                    <Link href="/login">Log in</Link>
                </li>

                <li v-if="loggedin" style="float: right">
                    <Link href="/logout" method="post">Log out</Link>
                </li>

                <li
                    :class="{ lo_active: pagename == '/about' }"
                    style="float: right"
                >
                    <Link href="/about">About</Link>
                </li>
            </ul>
        </nav>

        <section class="layout-content">
            <slot />
        </section>

        <footer class="layout-bottom">
            <p>
                A New Methodology and Ethics for Manuscript Studies
                (EthiCodex)<span style="float: right">v0.9</span>
            </p>
        </footer>
    </div>
</template>

<script>
export default {
    computed: {
        loggedin() {
            return this.$page.props.auth == null ? 0 : 1;
        },
        editor() {
            return this.$page.props.auth
                ? this.$page.props.auth.user.role.id >= 2
                : 0;
        },
        name() {
            return this.$page.props.auth == null
                ? ""
                : this.$page.props.auth.user.name;
        },
        role() {
            return this.$page.props.auth == null
                ? null
                : this.$page.props.auth.user.role;
        },
        pagename() {
            return this.$page.url;
        },
    },
};
</script>

<script setup>
import { ref } from "vue";

let isActive = ref("Home");

const props = defineProps({
    auth: Object,
});
</script>

<style>
/* ======================= */
/* Shared Styling Elements */
/* ======================= */

@import url("https://fonts.googleapis.com/css?family=Open+Sans");

* {
    font-family: "Open Sans", sans-serif;
}

*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html,
body {
    width: 90%;
    min-width: 1024px;
    height: 100%;
    margin: auto;
    background-color: #ddd;
    color: black;
}

.fullpage-container {
    padding-top: 0.5em;
    background-color: #fff;
    min-height: 100vh;
    display: grid;

    grid-template-rows: auto auto 1fr auto;
    grid-template-columns: auto 1fr auto;

    grid-template-areas:
        "logo header user"
        "logo navigation navigation"
        "content content content"
        "bottom bottom bottom";
}

.layout-logo {
    grid-area: logo;
    align-self: start;
    justify-self: start;
    padding-right: 1em;
}
.layout-logo img {
    width: 134px;
    height: 130px;
    object-fit: contain;
}

.layout-header {
    grid-area: header;
    align-self: center;
    justify-self: start;
    margin-left: 0em;
}

.layout-user {
    grid-area: user;
    align-self: center;
    margin-right: 1em;
}

.layout-navigation {
    grid-area: navigation;
}

.layout-navigation ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: black;
}

.layout-navigation li {
    float: left;
}

.layout-navigation li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.layout-navigation li a:hover:not(.active) {
    background-color: rgb(194, 181, 166);
}

.lo_active a {
    background-color: rgb(140, 150, 129);
}

.lo_active a:hover {
    background-color: rgb(119, 128, 111);
}

.layout-content {
    grid-area: content;
    background-color: white;
    padding-bottom: 0.5em;
}

.layout-bottom {
    grid-area: bottom;
    background-color: #000;
    color: #fff;
    text-align: center;
    width: 100%;
}

.pushleft {
    display: flex;
    justify-content: flex-start;
    gap: 20px;
}

.topbutton {
    float: right;
    padding: 0.3em 0.5em;
    margin-right: 10px;
}

.addbutton {
    padding: 2px 2px;
    margin-bottom: 20px;
    margin-top: 20px;
    height: fit-content;
    width: fit-content;
}

.ratio {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.ratio label {
    padding-top: 15px;
    padding-right: 15px;
    font-weight: bold;
}

.ratio_string {
    background-color: #ddd;
    border-style: none;
    border-radius: 5px;
    padding: 8px;
    margin: 4px;
    min-height: 2em;
    width: 10em;
    font-size: 16px;
}

.codexmenu {
    display: flex;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    width: 100%;
    justify-content: stretch;
}

.codexmenu button {
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 5px;
    font-size: 18px;
    width: 100%;
    transition: 50ms;
}

.codexmenu button.unselected {
    background-color: rgb(225, 225, 228);
}

.codexmenu button.unselected:hover {
    background-color: rgb(186, 189, 185);
}

.codexmenu button.selected:hover {
    background-color: rgb(77, 87, 66);
}

.codexmenu button.selected {
    background-color: rgb(119, 128, 111);
}

.mainstyle {
    min-height: 95%;
    background-color: #eee;
    padding: 20px;
}

.maincontainer {
    display: flex;
    background-color: #eee;
    font-family: sans-serif;
    font-size: 16px;
    flex-direction: column;
    width: 100%;
    height: auto;
    justify-content: center;
}

.submitbutton {
    font-size: 20px;
    width: 250px;
    height: 55px;
    padding: 10px;
    border-radius: 15px;
    justify-self: center;
    align-self: center;
    margin: 20px;
}

.submitbutton_red {
    font-size: 20px;
    width: 250px;
    height: 55px;
    padding: 10px;
    border-radius: 15px;
    justify-self: center;
    align-self: center;
    margin: 20px;
    background-color: #f50;
}

.submitbutton_red:hover {
    font-size: 20px;
    width: 250px;
    height: 55px;
    padding: 10px;
    border-radius: 15px;
    justify-self: center;
    align-self: center;
    margin: 20px;
    background-color: #f30;
}

.submitbutton_red:active {
    font-size: 20px;
    width: 250px;
    height: 55px;
    padding: 10px;
    border-radius: 15px;
    justify-self: center;
    align-self: center;
    margin: 20px;
    background-color: #b00;
}

.codex_imagebox {
    padding: 10px;
    background-color: #bbb;
    margin: 5px;
    display: flex;
    flex-direction: column;
    width: fit-content;
    max-width: 270px;
}

.codex_images_rows {
    display: inline-flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.removebutton {
    all: unset;
    cursor: pointer;
}

.sectionheading {
    background-color: #fff;
    border-style: solid;
    border-width: 1px;
    border-radius: 10px;
    padding: 5px 15px;
    font-size: 18px;
}

.dropdownbutton {
    display: flex;
    justify-content: space-between;
    background-color: #eee;
    width: 10em;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 5px 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdownbutton-wide {
    display: flex;
    justify-content: space-between;
    background-color: #eee;
    width: 400px;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 5px 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.closemenubutton {
    background-color: #eee;
    width: 10em;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 5px 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}
.dropdownbutton-dark {
    display: flex;
    justify-content: space-between;
    background-color: #ccc;
    width: 10em;
    height: fit-content;
    border-radius: 5px;
    margin-top: 10px;
    padding: 5px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown-content {
    display: flex;
    flex-direction: column;
    justify-items: flex-start;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    padding: 20px;
    border-radius: 10px;
}

.dropdown-element {
    display: flex;
    flex-direction: column;
}

.dropdown-content h3 {
    padding-top: 1em;
}

.choiceelement {
    display: inline-block;
    background-color: #fff; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: 220px; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 2px 10px 2px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 12px; /* Changing font size */
}

.choiceelement-short {
    display: inline-block;
    background-color: #fff; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: 160px; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 2px 10px 2px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 12px; /* Changing font size */
}

.choiceelement-wide {
    display: inline-block;
    background-color: #fff; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: 400px; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 2px 10px 2px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 12px; /* Changing font size */
}

.choicelist {
    margin-top: 10px;
}

.choicelist-stack {
    display: flex;
    flex-direction: column;
    width: auto;
    margin-top: 10px;
}

.dropdown-scrollwindow {
    max-height: 180px;
    overflow: auto;
}

.dropdown-scrollwindow label {
    padding-left: 6px;
}

.inputfield {
    display: flex;
    flex-direction: column;
}

.inputfield label {
    margin-top: 15px;
    margin-right: 15px;
    font-weight: bold;
}
.inputfield input {
    padding: 8px;
    margin: 4px;
    border-radius: 5px;
}

.inputfield textarea {
    resize: vertical;
    height: 100%;
    border-radius: 10px;
    padding: 8px;
}

.inputfield select {
    background-color: #eee;
    min-width: 10em;
    width: fit-content;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 5px 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.inputfield-search {
    display: flex;
    flex-direction: column;
}

.inputfield-search label {
    font-weight: bold;
}
.inputfield-search input {
    border-radius: 5px;
}

.input_ad_year {
    display: flex;
    flex-direction: row;
    align-items: center;
}

/* =============== */
/* Codices Styling */
/* =============== */

.pageline {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: flex-end;
    background-color: #eef;
    font-size: 14px;
}

.pageline_search_alternatives {
    margin-bottom: 15px;
    margin-right: 20px;
    margin-left: auto;
}
.pageline_search_field {
    flex-basis: 25%;
    margin-left: auto;
    margin-bottom: 15px;
}

.pageline button {
    margin-left: 10px;
    margin-right: 10px;
    padding: 5px 5px;
}

.searchblockbackground {
    background-color: #ccc;
    padding: 5px;
}

.searchblocktitle {
    position: absolute;
    top: 5px;
    left: 20px;
    font-size: 16px;
    color: #559;
    font-variant: small-caps;
    font-weight: bold;
}

.searchblock {
    position: relative;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    background-color: rgb(191, 193, 204);
    border-bottom-width: 2px;
    border-bottom-style: solid;
    border-bottom-color: #99b;
    padding: 20px 10px;
    margin-top: 8px;
    border-radius: 20px;
}

.searchblock-outer {
    position: relative;
    display: flex;
    flex-direction: column;
    background-color: rgb(191, 193, 204);
    border-bottom-width: 2px;
    border-bottom-style: solid;
    border-bottom-color: #99b;
    padding: 20px 10px;
    margin-top: 8px;
    border-radius: 20px;
}

.searchblock-inner {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
}

.codex_pagecontainer {
    align-self: flex-end;
    float: right;
    font-size: 20px;
    margin-bottom: 20px;
    margin-right: 10px;
}

.codex_pagecontainer a {
    all: unset;
    border-style: solid;
    border-width: 1px;
    border-color: #aab;
    background-color: #99b;
    color: #fff;
    padding: 5px;
    cursor: pointer;
}

.codex_pagecontainer a:hover {
    background-color: #669;
}

.codex_pages {
    padding: 1em;
}
.codex_pageactive {
    padding: 1em;
    background-color: #444 !important;
}

.cod_container {
    display: flex;
    border-style: none;
    padding: 1em;
    margin: 0.5em 0em;
    gap: 1em;
    background-color: #eee;
    width: 100%;
}

.cod_titles {
    display: flex;
    align-content: center;
    border-style: none;
    padding-bottom: 1em;
    margin-bottom: 0.5em;
    gap: 1em;
    background-color: #bbb;
    width: 100%;
}

.cod_buttons {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.cod_first {
    flex: 0 0 5%;
    align-self: center;
    justify-self: end;
}

.cod_second {
    flex: 0 0 18%;
    align-self: center;
    justify-self: end;
}
.cod_third {
    flex: 0 0 26%;
    align-self: center;
    justify-self: end;
}
.cod_fourth {
    flex: 0 0 26%;
    align-self: center;
    justify-self: end;
}
.cod_fifth {
    flex: 0 0 10%;
    align-self: center;
    justify-self: end;
}
.cod_sixth {
    flex: 0 0 10%;
    align-self: center;
    justify-self: end;
}

/* ================== */
/* Show Codex Styling */
/* ================== */

.show_published {
    grid-area: published;
    justify-self: start;
}

.show_trismegistos {
    grid-area: trismegistos;
    justify-self: start;
}
.show_standard {
    grid-area: standard;
}
.show_other {
    grid-area: other;
}
.show_publ {
    grid-area: publ;
}
.show_shelf {
    grid-area: shelf;
}
.show_bibliography {
    grid-area: biblio;
}

.showcodex_generalgrid {
    display: grid;
    grid-template-columns: 1fr 2fr 4fr 4fr;
    grid-template-rows: auto;
    grid-template-areas:
        "published . . ."
        "trismegistos standard standard other"
        "trismegistos standard standard other"
        "publ publ publ other"
        "publ publ publ biblio"
        "shelf shelf shelf biblio"
        "shelf shelf shelf biblio";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.show_title {
    grid-area: title;
}
.show_author {
    grid-area: author;
}
.show_contdesc {
    grid-area: desc;
}
.show_language {
    grid-area: language;
    display: flex;
    flex-direction: column;
}
.show_genre {
    grid-area: genre;
}
.show_content {
    grid-area: cont;
    display: flex;
    flex-direction: column;
}

.showcodex_contentgrid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
    grid-template-rows: 1fr auto;
    grid-template-areas:
        "title title title author author author desc desc desc"
        "language language genre genre cont cont desc desc desc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.show_early {
    grid-area: early;
}
.show_late {
    grid-area: late;
}
.show_datecomm {
    grid-area: datecomm;
}
.show_basis {
    grid-area: basis;
    display: flex;
    flex-direction: column;
}
.show_certainty {
    grid-area: certainty;
}

.showcodex_date {
    display: grid;
    grid-template-columns: 1fr 1fr 4fr;
    grid-template-rows: auto;
    grid-template-areas:
        "early late datecomm"
        "basis certainty datecomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.show_general {
    grid-area: general;
}
.show_internal {
    grid-area: internal;
}

.showcodex_comments {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "general internal"
        "general published";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.show_material {
    grid-area: material;
}
.show_ink {
    grid-area: ink;
    display: flex;
    flex-direction: column;
}
.show_inksdesc {
    grid-area: inksdesc;
}
.show_cover {
    grid-area: cover;
}
.show_coverdesc {
    grid-area: coverdesc;
}

.show_structure {
    grid-area: structure;
}
.show_numquire {
    grid-area: numquire;
}
.show_quirenum {
    grid-area: quirenum;
    display: flex;
    flex-direction: column;
}
.show_quirecomm {
    grid-area: quirecomm;
}
.show_bindingdesc {
    grid-area: bindingdesc;
}

.showcodex_materiality {
    display: grid;
    grid-template-columns: 1fr 4fr;
    grid-template-rows: auto;
    grid-template-areas:
        "material bindingdesc"
        "ink inksdesc"
        "cover coverdesc"
        "structure quirecomm"
        "numquire quirecomm"
        "quirenum quirecomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.show_dimknown {
    grid-area: dimknown;
}
.show_meascomm {
    grid-area: meascomm;
}
.show_pagewidth {
    grid-area: pagewidth;
}
.show_pageheight {
    grid-area: pageheight;
}
.show_pratio {
    grid-area: pratio;
    margin-top: -18px;
}
.show_stable {
    grid-area: stable;
}
.show_blockwidth {
    grid-area: blockwidth;
}
.show_blockheight {
    grid-area: blockheight;
}
.show_bratio {
    grid-area: bratio;
    margin-top: -18px;
}
.show_uppmarg {
    grid-area: uppmarg;
}
.show_lowmarg {
    grid-area: lowmarg;
}
.show_ulratio {
    grid-area: ulratio;
    margin-top: -18px;
}
.show_innmarg {
    grid-area: innmarg;
}
.show_outmarg {
    grid-area: outmarg;
}
.show_ioratio {
    grid-area: ioratio;
    margin-top: -18px;
}

.showcodex_measurements {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 2fr;
    grid-template-rows: auto;
    grid-template-areas:
        "dimknown . . meascomm"
        "pagewidth pageheight pratio meascomm"
        "stable . . meascomm"
        "blockwidth blockheight bratio meascomm"
        "uppmarg lowmarg ulratio meascomm"
        "innmarg outmarg ioratio meascomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.show_numhands {
    grid-area: numhands;
}
.show_scripts {
    grid-area: scripts;
    display: flex;
    flex-direction: column;
}
.show_scriptdesc {
    grid-area: scriptdesc;
}
.show_pagination {
    grid-area: pagination;
}
.show_paginationdesc {
    grid-area: paginationdesc;
}
.show_paratext {
    grid-area: paratext;
    display: flex;
    flex-direction: column;
}
.show_paradesc {
    grid-area: paradesc;
}
.show_punctuation {
    grid-area: punctuation;
    display: flex;
    flex-direction: column;
}
.show_punctuationdesc {
    grid-area: punctuationdesc;
}
.show_diacritics {
    grid-area: diacritics;
    display: flex;
    flex-direction: column;
}
.show_diacritdesc {
    grid-area: diacritdesc;
}
.show_critical {
    grid-area: critical;
    display: flex;
    flex-direction: column;
}
.show_criticaldesc {
    grid-area: criticaldesc;
    display: flex;
    flex-direction: column;
}
.show_decorative {
    grid-area: decorative;
    display: flex;
    flex-direction: column;
}
.show_decodesc {
    grid-area: decodesc;
}
.show_quiresig {
    grid-area: quiresig;
}
.show_quiresigdesc {
    grid-area: quiresigdesc;
}

.showcodex_textualfeatures {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "scripts scripts scripts numhands diacritics diacritics"
        "scriptdesc scriptdesc scriptdesc scriptdesc diacritdesc diacritdesc"
        "punctuation punctuation critical critical paratext paratext"
        "punctuationdesc punctuationdesc criticaldesc criticaldesc paradesc paradesc"
        "decorative decorative pagination pagination quiresig quiresig"
        "decodesc decodesc paginationdesc paginationdesc quiresigdesc quiresigdesc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.show_storage {
    grid-area: storage;
}
.show_scientific {
    grid-area: scientific;
}
.show_conshist {
    grid-area: conshist;
}
.show_analyscomm {
    grid-area: analyscomm;
}

.showcodex_conservanalys {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "storage scientific"
        "conshist analyscomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.show_sciex {
    grid-area: sciex;
}
.show_excomm {
    grid-area: excomm;
}
.show_anciprov {
    grid-area: anciprov;
}
.show_ancicert {
    grid-area: ancicert;
}
.show_ancicomm {
    grid-area: ancicomm;
}
.show_transactions {
    grid-area: transactions;
}
.show_legalclass {
    grid-area: legalclass;
}
.show_legalcomm {
    grid-area: legalcomm;
}

.show_transinfo {
    grid-area: transinfo;
}

.showcodex_provenance {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "sciex anciprov ancicert"
        "excomm ancicomm ancicomm"
        "legalclass legalcomm legalcomm"
        "transactions transinfo transinfo";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.show_img {
    grid-area: img;
}

.showcodex_images {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: auto;
    grid-template-areas: "img";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.showcodex_labelpadding {
    padding-top: 15px;
    padding-right: 15px;
}

.showcodexcontainer label {
    font-size: 18px;
    font-weight: bold;
}

.showcodex_string {
    background-color: #ddd;
    padding: 5px;
    border-style: none;
    border-radius: 5px;
    min-height: 2em;
}

.showcodex_string_short {
    background-color: #ddd;
    padding: 5px;
    border-style: none;
    border-radius: 5px;
    min-height: 2em;
    width: 10em;
}

.showcodex_text {
    background-color: #ddd;
    padding: 10px;
    border-style: none;
    border-radius: 5px;
    min-height: 5em;
}

.showcodex_text ul {
    list-style: none;
    margin-left: 20px;
    text-indent: -20px;
}

.showcodex_text h1 {
    margin-top: 10px;
    font-size: large;
    font-weight: bold;
}
.showcodex_text h2 {
    margin-top: 10px;
    font-size: medium;
    font-weight: bold;
}

.showcodex_bool {
    background-color: #ddd;
    padding: 5px;
    border-style: none;
    border-radius: 5px;
    min-height: 2em;
    width: 5em;
}

.showcodex_multi {
    display: inline-block;
}

.showcodex_multi_line {
    display: inline-block;
    background-color: #eee; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: fit-content; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 5px 10px 5px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 16px; /* Changing font size */
}

.showcodex_multi_stack {
    background-color: #eee; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: auto; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 5px 10px 5px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 16px; /* Changing font size */
}

/* ================== */
/* Edit Codex Styling */
/* ================== */

.editcodex_sectionheading {
    background-color: #fff;
    border-style: solid;
    border-width: 1px;
    border-radius: 10px;
    padding: 5px 15px;
    font-size: 18px;
}

.edit_published {
    grid-area: published;
    align-self: center;
}
.edit_trismegistos {
    grid-area: trismegistos;
}
.edit_standard {
    grid-area: standard;
}
.edit_other {
    grid-area: other;
}
.edit_publ {
    grid-area: publ;
}
.edit_shelf {
    grid-area: shelf;
}
.edit_bibliography {
    grid-area: biblio;
}

.editcodex_generalgrid {
    display: grid;
    grid-template-columns: 1fr 1fr 4fr 4fr;
    grid-template-rows: auto;
    grid-template-areas:
        "published trismegistos standard other"
        "published trismegistos standard other"
        "publ publ publ other"
        "publ publ publ biblio"
        "shelf shelf shelf biblio"
        "shelf shelf shelf biblio";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.edit_title {
    grid-area: title;
}
.edit_author {
    grid-area: author;
}
.edit_contdesc {
    grid-area: desc;
}
.edit_language {
    grid-area: language;
}
.edit_genre {
    grid-area: genre;
}
.edit_content {
    grid-area: cont;
}

.editcodex_contentgrid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
    grid-template-rows: 1fr auto;
    grid-template-areas:
        "title title title author author author desc desc desc"
        "language language genre genre cont cont desc desc desc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.edit_early {
    grid-area: early;
}
.edit_late {
    grid-area: late;
}
.edit_datecomm {
    grid-area: datecomm;
}
.edit_basis {
    grid-area: basis;
}
.edit_certainty {
    grid-area: certainty;
}

.editcodex_date {
    display: grid;
    grid-template-columns: 1fr 1fr 4fr;
    grid-template-rows: auto;
    grid-template-areas:
        "early late datecomm"
        "basis certainty datecomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.edit_general {
    grid-area: general;
}
.edit_internal {
    grid-area: internal;
}

.editcodex_comments {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas: "general internal";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.edit_material {
    grid-area: material;
}
.edit_ink {
    grid-area: ink;
}
.edit_inksdesc {
    grid-area: inksdesc;
}
.edit_cover {
    grid-area: cover;
}
.edit_coverdesc {
    grid-area: coverdesc;
}
.edit_structure {
    grid-area: structure;
}
.edit_numquire {
    grid-area: numquire;
}
.edit_quirenum {
    grid-area: quirenum;
}
.edit_quirecomm {
    grid-area: quirecomm;
}
.edit_bindingdesc {
    grid-area: bindingdesc;
}

.editcodex_materiality {
    display: grid;
    grid-template-columns: 1fr 4fr;
    grid-template-rows: auto;
    grid-template-areas:
        "material bindingdesc"
        "ink inksdesc"
        "cover coverdesc"
        "structure quirecomm"
        "numquire quirecomm"
        "quirenum quirecomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.edit_dimknown {
    grid-area: dimknown;
}
.edit_meascomm {
    grid-area: meascomm;
}
.edit_pagewidth {
    grid-area: pagewidth;
}
.edit_pageheight {
    grid-area: pageheight;
}
.edit_pratio {
    grid-area: pratio;
}
.edit_stable {
    grid-area: stable;
}
.edit_blockwidth {
    grid-area: blockwidth;
}
.edit_blockheight {
    grid-area: blockheight;
}
.edit_bratio {
    grid-area: bratio;
}
.edit_uppmarg {
    grid-area: uppmarg;
}
.edit_lowmarg {
    grid-area: lowmarg;
}
.edit_ulratio {
    grid-area: ulratio;
}
.edit_innmarg {
    grid-area: innmarg;
}
.edit_outmarg {
    grid-area: outmarg;
}
.edit_ioratio {
    grid-area: ioratio;
}

.editcodex_measurements {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 2fr;
    grid-template-rows: auto;
    grid-template-areas:
        "dimknown . . meascomm"
        "pagewidth pageheight pratio meascomm"
        "stable . . meascomm"
        "blockwidth blockheight bratio meascomm"
        "uppmarg lowmarg ulratio meascomm"
        "innmarg outmarg ioratio meascomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.edit_numhands {
    grid-area: numhands;
    margin-right: auto;
}
.edit_scripts {
    grid-area: scripts;
}
.edit_scriptdesc {
    grid-area: scriptdesc;
}
.edit_pagination {
    grid-area: pagination;
}
.edit_paginationdesc {
    grid-area: paginationdesc;
}
.edit_paratext {
    grid-area: paratext;
}
.edit_paradesc {
    grid-area: paradesc;
}
.edit_punctuation {
    grid-area: punctuation;
}
.edit_punctuationdesc {
    grid-area: punctuationdesc;
}
.edit_diacritics {
    grid-area: diacritics;
}
.edit_diacritdesc {
    grid-area: diacritdesc;
}
.edit_critical {
    grid-area: critical;
}
.edit_criticaldesc {
    grid-area: criticaldesc;
}
.edit_decorative {
    grid-area: decorative;
}
.edit_decodesc {
    grid-area: decodesc;
}
.edit_quiresig {
    grid-area: quiresig;
}
.edit_quiresigdesc {
    grid-area: quiresigdesc;
}

.editcodex_textualfeatures {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "scripts scripts scripts numhands diacritics diacritics"
        "scriptdesc scriptdesc scriptdesc scriptdesc diacritdesc diacritdesc"
        "punctuation punctuation critical critical paratext paratext"
        "punctuationdesc punctuationdesc criticaldesc criticaldesc paradesc paradesc"
        "decorative decorative pagination pagination quiresig quiresig"
        "decodesc decodesc paginationdesc paginationdesc quiresigdesc quiresigdesc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
}

.edit_storage {
    grid-area: storage;
}
.edit_scientific {
    grid-area: scientific;
}
.edit_conshist {
    grid-area: conshist;
}
.edit_analyscomm {
    grid-area: analyscomm;
}

.editcodex_conservanalys {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "storage scientific"
        "conshist analyscomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.edit_sciex {
    grid-area: sciex;
}
.edit_excomm {
    grid-area: excomm;
}
.edit_anciprov {
    grid-area: anciprov;
}
.edit_ancicert {
    grid-area: ancicert;
}
.edit_ancicomm {
    grid-area: ancicomm;
}
.edit_transactions {
    grid-area: transactions;
}
.edit_legalclass {
    grid-area: legalclass;
}
.edit_legalcomm {
    grid-area: legalcomm;
}

.editcodex_provenance {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "sciex anciprov ancicert"
        "excomm ancicomm ancicomm"
        "transactions legalclass legalclass"
        "transactions legalcomm legalcomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.edit_img {
    grid-area: img;
}

.editcodex_images {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: auto;
    grid-template-areas: "img";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

.labelpadding {
    padding-top: 15px;
    padding-right: 15px;
}

/* ======================== */
/* Transaction List Styling */
/* ======================== */

.transactioncontainer {
    display: flex;
    border-style: none;
    padding: 1em;
    margin: 0.5em 0em;
    gap: 1em;
    background-color: #eee;
    width: 100%;
}

.pur_first {
    flex: 0 0 5%;
    align-self: center;
    justify-self: end;
}

.pur_second {
    flex: 0 0 7%;
    align-self: center;
    justify-self: end;
}
.pur_third {
    flex: 0 0 20%;
    align-self: center;
    justify-self: end;
}
.pur_fourth {
    flex: 0 0 30%;
    align-self: center;
    justify-self: end;
}
.pur_fifth {
    flex: 0 0 14%;
    align-self: center;
    justify-self: end;
}
.pur_sixth {
    flex: 0 0 15%;
    align-self: center;
    justify-self: end;
}

/* ============================ */
/* Add/Edit Transaction Styling */
/* ============================ */

.trans_year {
    grid-area: year;
}
.trans_month {
    grid-area: month;
}
.trans_day {
    grid-area: day;
}
.trans_transaction {
    grid-area: transaction;
}
.trans_transdesc {
    grid-area: transdesc;
}
.trans_transbiblio {
    grid-area: transbiblio;
}
.trans_parties {
    grid-area: parties;
}
.trans_docs {
    grid-area: docs;
}

.transaction_grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 50%;
    grid-template-rows: auto;
    grid-template-areas:
        "transaction transaction transaction transaction transaction transaction transdesc"
        "year year month month day day transdesc"
        "parties parties parties docs docs docs transbiblio";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

/* ============================== */
/* Transaction Party List Styling */
/* ============================== */

.partycontainer {
    display: flex;
    border-style: none;
    padding: 1em;
    margin: 0.5em 0em;
    gap: 1em;
    background-color: #eee;
    width: 100%;
}

.par_first {
    flex: 0 0 5%;
    align-self: center;
    justify-self: end;
}

.par_second {
    flex: 0 0 18%;
    align-self: center;
    justify-self: end;
}
.par_third {
    flex: 0 0 30%;
    align-self: center;
    justify-self: end;
}
.par_fourth {
    flex: 0 0 15%;
    align-self: center;
    justify-self: end;
}
.par_fifth {
    flex: 0 0 25%;
    align-self: center;
    justify-self: end;
}

/* ============================ */
/* Add / Edit Transaction Party */
/* ============================ */

.party_transparty {
    grid-area: transparty;
}
.party_institution {
    grid-area: institution;
    margin-top: 16px;
    margin-left: 30px;
    display: flex;
    flex-direction: column;
    justify-items: center;
}
.party_partydesc {
    grid-area: partydesc;
}
.party_transactions {
    grid-area: transactions;
}

.party_partygrid {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr;
    grid-template-rows: auto;
    grid-template-areas:
        "transparty institution partydesc"
        "transactions transactions partydesc";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
}

/* ================== */
/* About Page Styling */
/* ================== */

.aboutcontainer h1 {
    font-size: larger;
    margin-top: 1em;
}

.aboutcontainer h2 {
    font-size: medium;
}

.aboutcontainer {
    width: 60%;
    margin: auto;
}

.aboutcontainer p {
    margin-top: 1em;
}

/* ================== */
/* Login Page Styling */
/* ================== */

.logincontainer {
    width: 45%;
    display: flex;
    background-color: #eee;
    margin: auto;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    flex-direction: column;
}

.loginline {
    margin-top: 1em;
}

.loginline button {
    margin-left: 2em;
    padding: 0.5em;
}

/* ===================== */
/* Register Page Styling */
/* ===================== */

.registercontainer {
    width: 45%;
    display: flex;
    background-color: #eee;
    margin: auto;
    padding: 20px;
    font-family: sans-serif;
    font-size: 14px;
    flex-direction: column;
}

.registercontainer button {
    margin-top: 2em;
    padding: 0.5em;
}
</style>
