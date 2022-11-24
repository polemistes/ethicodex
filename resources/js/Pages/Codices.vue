<template>
    <div class="pageline">
        <Link
            v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
            class="pages"
            href="/codex_new"
            as="button"
        >
            Add Codex
        </Link>

        <div class="codex_pagecontainer">
            <Link
                v-for="link in documents.links"
                :key="link.label"
                :href="link.url"
                v-html="link.label"
                :class="{
                    codex_pageactive: this.pagenr == link.label,
                    codex_pages: this.pagenr != link.label,
                }"
            />
        </div>
    </div>

    <div class="cod_container">
        <div class="cod_first"></div>
        <div class="cod_second"><b>Standard Name</b></div>
        <div class="cod_third"><b>Shelfmark</b></div>
        <div class="cod_fourth"><b>Publication</b></div>
        <div class="cod_fifth"><b>Trismegistos ID</b></div>
        <div class="cod_sixth"><b>Date</b></div>
    </div>
    <div class="cod_container" style="background-color: #bbb">
        <div class="cod_first">Search:</div>

        <div class="cod_second">
            <input
                v-model="search_standard"
                @keyup="sendsearch()"
                class="searchfield"
                type="text"
            />
        </div>

        <div class="cod_third">
            <input
                v-model="search_shelf"
                @keyup="sendsearch()"
                class="searchfield"
                type="text"
            />
        </div>

        <div class="cod_fourth">
            <input
                v-model="search_pub"
                @keyup="sendsearch()"
                class="searchfield"
                type="text"
            />
        </div>

        <div class="cod_fifth">
            <input
                v-model="search_tri"
                @keyup="sendsearch()"
                class="searchfield"
                type="text"
            />
        </div>

        <div class="cod_sixth">
            <span class="searchfield">
                <input
                    v-model="search_from"
                    @keyup="sendsearch()"
                    size="3"
                    type="text"
                />
                –
                <input
                    v-model="search_to"
                    @keyup="sendsearch()"
                    size="3"
                    type="text"
                />
            </span>
        </div>
    </div>

    <div
        class="cod_container"
        v-for="document in documents.data"
        :key="document.id"
    >
        <div class="cod_first cod_buttons">
            <Link
                as="button"
                type="button"
                :href="'/codex_show/' + document.id"
                style="margin-right: 1em; padding: 0.3em 0.5em"
                >Show
            </Link>

            <Link
                as="button"
                type="button"
                v-if="
                    props.auth == null
                        ? 0
                        : props.auth.user.role.id >= 2
                        ? 1
                        : 0
                "
                :href="'/codex_edit/' + document.id"
                style="margin-right: 1em; padding: 0.3em 0.5em"
            >
                Edit
            </Link>
            <Link
                as="button"
                type="button"
                v-if="
                    props.auth == null
                        ? 0
                        : props.auth.user.role.id >= 2
                        ? 1
                        : 0
                "
                onclick="return confirm('Are you sure you want to delete this codex?')"
                :href="'/codex_delete/' + document.id"
                method="post"
                style="margin-right: 1em; padding: 0.3em 0.5em"
            >
                Delete
            </Link>
        </div>

        <div class="cod_second" v-text="document.standard_name"></div>
        <div class="cod_third" v-text="document.current_shelfmarks"></div>
        <div class="cod_fourth" v-text="document.publication"></div>
        <div class="cod_fifth" v-text="document.trismegistos_id"></div>
        <div
            class="cod_sixth"
            v-text="document.start_year + '–' + document.end_year"
        ></div>
    </div>
    <div class="codex_pageline">
        <div class="codex_pagecontainer">
            <Link
                v-for="link in documents.links"
                :key="link.label"
                :href="link.url"
                v-html="link.label"
                :class="{
                    codex_pageactive: this.pagenr == link.label,
                    codex_pages: this.pagenr != link.label,
                }"
            />
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        pagenr() {
            const urlParams = new URLSearchParams(this.$page.url);
            let pagenr = urlParams.get("/codices?page");
            for (const key of urlParams.keys()) console.log(key);
            console.log(pagenr);

            return pagenr > 0 ? pagenr : 1;
        },
    },
};
</script>

<script setup>
import { ref, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    documents: Object,
    search_standard: String,
    search_shelf: String,
    search_pub: String,
    search_tri: String,
    search_from: String,
    search_to: String,
    auth: Object,
});

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

let search_standard = ref(props.search_standard);
let search_shelf = ref(props.search_shelf);
let search_pub = ref(props.search_pub);
let search_tri = ref(props.search_tri);
let search_from = ref(props.search_from);
let search_to = ref(props.search_to);

function sendsearch() {
    Inertia.get(
        "/codices",
        {
            search_standard: search_standard.value,
            search_shelf: search_shelf.value,
            search_pub: search_pub.value,
            search_tri: search_tri.value,
            search_from: search_from.value,
            search_to: search_to.value,
        },
        { preserveState: true, preserveScroll: true }
    );
}
</script>

<style></style>
