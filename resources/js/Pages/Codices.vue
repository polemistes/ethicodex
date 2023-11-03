<template>
    <div class="pageline">
        <Link
            v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
            class="addbutton"
            href="/codex_new"
            as="button"
        >
            Add Codex
        </Link>
        <div class="pageline_search_field">
            <EthInput
                input_type="textevent"
                input_id="fulltext"
                v-model="form.fulltext"
                @key-pressed="sendsearch()"
            >
                Full-Text Search
            </EthInput>
        </div>

        <div class="pageline_total_found">
            Codices found: {{ documents.total }}
        </div>
        <div class="pageline_search_alternatives">
            <EthInput
                input_type="multi_choice_search"
                input_id="showfields"
                :choices="allfields"
                v-model:publication="form.show_publication"
                v-model:content="form.show_content"
                v-model:dating="form.show_dating"
                v-model:materiality="form.show_materiality"
                v-model:measurement="form.show_measurement"
                v-model:palaeography="form.show_palaeography"
                v-model:consanal="form.show_consanal"
                v-model:provenance="form.show_provenance"
                @new-change="sendsearch()"
            >
                Search Alternatives
            </EthInput>
        </div>

        <div class="codex_pagecontainer" style="margin-left: auto">
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
    <div class="searchblockbackground">
        <div v-if="form.show_publication" class="searchblock">
            <div class="searchblocktitle">Publication Search</div>
            <EthInput
                input_type="textevent"
                input_id="standard_name"
                v-model="form.standard_name"
                @key-pressed="sendsearch()"
            >
                Standard Name
            </EthInput>
            <EthInput
                input_type="textevent"
                input_id="current_shelfmarks"
                v-model="form.current_shelfmarks"
                @key-pressed="sendsearch()"
            >
                Current Shelfmarks
            </EthInput>
            <EthInput
                input_type="textevent"
                input_id="publication"
                v-model="form.publication"
                @key-pressed="sendsearch()"
            >
                Publication
            </EthInput>
            <EthInput
                input_type="textevent"
                input_id="trismegistos_id"
                v-model="form.trismegistos_id"
                @key-pressed="sendsearch()"
            >
                Trismegistos ID
            </EthInput>
        </div>

        <div v-if="form.show_content" class="searchblock">
            <div class="searchblocktitle">Content Search</div>
            <EthInput
                input_type="textevent"
                input_id="title"
                v-model="form.title"
                @key-pressed="sendsearch()"
            >
                Title of Work
            </EthInput>

            <EthInput
                input_type="textevent"
                input_id="ancient_author"
                v-model="form.ancient_author"
                @key-pressed="sendsearch()"
            >
                Ancient Author
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="genres"
                :choices="genres"
                v-model="form.genres"
                @new-change="sendsearch()"
            >
                Genres
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="tags"
                :choices="tags"
                v-model="form.tags"
                @new-change="sendsearch()"
            >
                Content Tags
            </EthInput>
        </div>

        <div v-if="form.show_dating" class="searchblock">
            <div class="searchblocktitle">Date Search</div>
            <div class="pushleft">
                <EthInput
                    input_type="numberevent"
                    input_id="earliest_date"
                    v-model="form.earliest_date"
                    @key-pressed="sendsearch()"
                >
                    Earliest Date
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="latest_date"
                    v-model="form.latest_date"
                    @key-pressed="sendsearch()"
                >
                    Latest Date
                </EthInput>

                <EthInput
                    helptext="Restrict search to codices where the full date range falls within the min and max search parameters."
                    input_type="boolevent"
                    input_id="exclusive_date"
                    v-model="form.exclusive_date"
                    @new-change="sendsearch()"
                >
                    Strict Date
                </EthInput>
            </div>
            <EthInput
                input_type="multi_choice_event"
                input_id="dating_methods"
                :choices="dating_methods"
                v-model="form.dating_methods"
                @new-change="sendsearch()"
            >
                Basis of Date
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="dating_certainties"
                :choices="dating_certainties"
                v-model="form.dating_certainties"
                @new-change="sendsearch()"
            >
                Certainty of Date
            </EthInput>
        </div>

        <div v-if="form.show_materiality" class="searchblock">
            <div class="searchblocktitle">Materiality Search</div>
            <EthInput
                input_type="multi_choice_event"
                input_id="materials"
                :choices="materials"
                v-model="form.materials"
                @new-change="sendsearch()"
            >
                Material
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="inks"
                :choices="inks"
                v-model="form.inks"
                @new-change="sendsearch()"
            >
                Ink
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="covers"
                :choices="covers"
                v-model="form.covers"
                @new-change="sendsearch()"
            >
                Cover
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="quire_structures"
                :choices="quire_structures"
                v-model="form.quire_structures"
                @new-change="sendsearch()"
            >
                Quire Structure
            </EthInput>

            <EthInput
                input_type="numberevent"
                input_id="quirenum_min"
                v-model="form.quirenum_min"
                @key-pressed="sendsearch()"
            >
                Min Num of Quires
            </EthInput>

            <EthInput
                input_type="numberevent"
                input_id="quirenum_max"
                v-model="form.quirenum_max"
                @key-pressed="sendsearch()"
            >
                Max Num of Quires
            </EthInput>

            <EthInput
                input_type="numberevent"
                input_id="bifolianum_min"
                v-model="form.bifolianum_min"
                @key-pressed="sendsearch()"
            >
                Min Num of Bifolia
            </EthInput>

            <EthInput
                input_type="numberevent"
                input_id="bifolianum_max"
                v-model="form.bifolianum_max"
                @key-pressed="sendsearch()"
            >
                Max Num of Bifolia
            </EthInput>
        </div>

        <div v-if="form.show_measurement" class="searchblock-outer">
            <div class="searchblocktitle">Measurements Search</div>
            <div class="searchblock-inner">
                <EthInput
                    input_type="numberevent"
                    input_id="full_page_width_min"
                    v-model="form.full_page_width_min"
                    @key-pressed="sendsearch()"
                >
                    Min Page Width
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_page_width_max"
                    v-model="form.full_page_width_max"
                    @key-pressed="sendsearch()"
                >
                    Max Page Width
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_page_height_min"
                    v-model="form.full_page_height_min"
                    @key-pressed="sendsearch()"
                >
                    Min Page Height
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_page_height_max"
                    v-model="form.full_page_height_max"
                    @key-pressed="sendsearch()"
                >
                    Max Page Height
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_text_block_width_min"
                    v-model="form.full_text_block_width_min"
                    @key-pressed="sendsearch()"
                >
                    Min Block Width
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_text_block_width_max"
                    v-model="form.full_text_block_width_max"
                    @key-pressed="sendsearch()"
                >
                    Max Block Width
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_text_block_height_min"
                    v-model="form.full_text_block_height_min"
                    @key-pressed="sendsearch()"
                >
                    Min Block Height
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="full_text_block_height_max"
                    v-model="form.full_text_block_height_max"
                    @key-pressed="sendsearch()"
                >
                    Max Block Height
                </EthInput>
            </div>
            <div class="searchblock-inner">
                <EthInput
                    input_type="numberevent"
                    input_id="upper_margin_min"
                    v-model="form.upper_margin_min"
                    @key-pressed="sendsearch()"
                >
                    Min Upper Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="upper_margin_max"
                    v-model="form.upper_margin_max"
                    @key-pressed="sendsearch()"
                >
                    Max Upper Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="lower_margin_min"
                    v-model="form.lower_margin_min"
                    @key-pressed="sendsearch()"
                >
                    Min Lower Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="lower_margin_max"
                    v-model="form.lower_margin_max"
                    @key-pressed="sendsearch()"
                >
                    Max Lower Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="inner_margin_min"
                    v-model="form.inner_margin_min"
                    @key-pressed="sendsearch()"
                >
                    Min Inner Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="inner_margin_max"
                    v-model="form.inner_margin_max"
                    @key-pressed="sendsearch()"
                >
                    Max Inner Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="outer_margin_min"
                    v-model="form.outer_margin_min"
                    @key-pressed="sendsearch()"
                >
                    Min Outer Margin
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="outer_margin_max"
                    v-model="form.outer_margin_max"
                    @key-pressed="sendsearch()"
                >
                    Max Outer Margin
                </EthInput>
            </div>
        </div>

        <div v-if="form.show_palaeography" class="searchblock-outer">
            <div class="searchblocktitle">Palaeography Search</div>
            <div class="searchblock-inner">
                <EthInput
                    input_type="numberevent"
                    input_id="hand_number_min"
                    v-model="form.hand_number_min"
                    @key-pressed="sendsearch()"
                >
                    Min Num of Hands
                </EthInput>

                <EthInput
                    input_type="numberevent"
                    input_id="hand_number_max"
                    v-model="form.hand_number_max"
                    @key-pressed="sendsearch()"
                >
                    Max Num of Hands
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="scripts"
                    :choices="scripts"
                    v-model="form.scripts"
                    @new-change="sendsearch()"
                >
                    Scripts
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="diacritics"
                    :choices="diacritics"
                    v-model="form.diacritics"
                    @new-change="sendsearch()"
                >
                    Diacritics
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="punctuations"
                    :choices="punctuations"
                    v-model="form.punctuations"
                    @new-change="sendsearch()"
                >
                    Punctuation
                </EthInput>
            </div>
            <div class="searchblock-inner">
                <EthInput
                    input_type="multi_choice_event"
                    input_id="paratexts"
                    :choices="paratexts"
                    v-model="form.paratexts"
                    @new-change="sendsearch()"
                >
                    Paratexts
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="decorations"
                    :choices="decorations"
                    v-model="form.decorations"
                    @new-change="sendsearch()"
                >
                    Decorative Symbols
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="critical_symbols"
                    :choices="critical_symbols"
                    v-model="form.critical_symbols"
                    @new-change="sendsearch()"
                >
                    Critical Symbols
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="paginations"
                    :choices="paginations"
                    v-model="form.paginations"
                    @new-change="sendsearch()"
                >
                    Pagination
                </EthInput>

                <EthInput
                    input_type="multi_choice_event"
                    input_id="quire_signatures"
                    :choices="quire_signatures"
                    v-model="form.quire_signatures"
                    @new-change="sendsearch()"
                >
                    Quire Signatures
                </EthInput>
            </div>
        </div>
        <div v-if="form.show_consanal" class="searchblock">
            <div class="searchblocktitle">Conservation and Analysis Search</div>
            <EthInput
                input_type="multi_choice_event"
                input_id="storage_conditions"
                :choices="storage_conditions"
                v-model="form.storage_conditions"
                @new-change="sendsearch()"
            >
                Storage Conditions
            </EthInput>

            <EthInput
                input_type="multi_choice_event_wide"
                input_id="analyses"
                :choices="analyses"
                v-model="form.analyses"
                @new-change="sendsearch()"
            >
                Scientific Analysis
            </EthInput>
        </div>

        <div v-if="form.show_provenance" class="searchblock">
            <div class="searchblocktitle">Provenance Search</div>
            <EthInput
                input_type="multi_choice_event"
                input_id="ancient_provenances"
                :choices="ancient_provenances"
                v-model="form.ancient_provenances"
                @new-change="sendsearch()"
            >
                Ancient Provenance
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="ancient_provenance_certainties"
                :choices="ancient_provenance_certainties"
                v-model="form.ancient_provenance_certainties"
                @new-change="sendsearch()"
            >
                Certainty of A. Provenance
            </EthInput>

            <EthInput
                input_type="single_choice_event"
                input_id="scientifically_excavated"
                :choices="yesnomenu"
                v-model="form.scientifically_excavated"
                @new-change="sendsearch()"
            >
                Scientifically Excavated
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="transactions"
                :choices="transactions"
                v-model="form.transactions"
                @new-change="sendsearch()"
            >
                Transactions
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="transaction_parties"
                :choices="transaction_parties"
                v-model="form.transaction_parties"
                @new-change="sendsearch()"
            >
                Transaction Parties
            </EthInput>

            <EthInput
                input_type="multi_choice_event"
                input_id="legal_classifications"
                :choices="legal_classifications"
                v-model="form.legal_classifications"
                @new-change="sendsearch()"
            >
                Legal Classification
            </EthInput>
        </div>
    </div>
    <div class="cod_titles" style="padding-top: 15px; background-color: #bbb">
        <div class="cod_first"></div>
        <div class="cod_second"><b>Standard Name</b></div>
        <div class="cod_third"><b>Shelfmark</b></div>
        <div class="cod_fourth"><b>Publication</b></div>
        <div class="cod_fifth"><b>Trismegistos ID</b></div>
        <div class="cod_sixth"><b>Date</b></div>
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
        <div class="cod_fourth" v-html="document.publication"></div>
        <div class="cod_fifth" v-text="document.trismegistos_id"></div>
        <div
            class="cod_sixth"
            v-text="document.start_year + '–' + document.end_year"
        ></div>
    </div>
    <div class="pageline">
        <div
            class="codex_pagecontainer"
            style="margin-left: auto; margin-top: 20px"
        >
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
            let url = new URL(window.location.href);
            let pagenr = url.searchParams.get("page");
            return pagenr > 0 ? pagenr : 1;
            /*
            const urlParams = new URLSearchParams(this.$page.url);
            let pagenr = urlParams.get("?page");
            return pagenr > 0 ? pagenr : 1;
            */
        },
    },
};
</script>

<script setup>
import { ref, reactive, watch, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import EthInput from "../Components/EthInput.vue";

const props = defineProps({
    documents: Object,
    analyses: Array,
    critical_symbols: Array,
    covers: Array,
    dating_certainties: Array,
    dating_methods: Array,
    decorations: Array,
    diacritics: Array,
    genres: Array,
    inks: Array,
    languages: Array,
    legal_classifications: Array,
    materials: Array,
    paginations: Array,
    paratexts: Array,
    punctuations: Array,
    purchases: Array,
    quire_signatures: Array,
    quire_structures: Array,
    scripts: Array,
    storage_conditions: Array,
    tags: Array,
    ancient_provenances: Array,
    ancient_provenance_certainties: Array,
    transactions: Array,
    transaction_parties: Array,

    show_publication: Boolean,
    show_content: Boolean,
    show_dating: Boolean,
    show_materiality: Boolean,
    show_measurement: Boolean,
    show_palaeography: Boolean,
    show_consanal: Boolean,
    show_provenance: Boolean,

    fulltext: String,
    /* Publication */
    standard_name: String,
    publication: String,
    current_shelfmarks: String,
    trismegistos_id: String,
    /* Content */
    title: String,
    ancient_author: String,
    genres_search: Array,
    tags_search: Array,
    /* Dating */
    earliest_date: Number,
    latest_date: Number,
    exclusive_date: Boolean,
    dating_methods_search: Array,
    dating_certainties_search: Array,
    /* Materiality */
    materials_search: Array,
    inks_search: Array,
    covers_search: Array,
    quire_structures_search: Array,
    quirenum_min: Number,
    quirenum_max: Number,
    bifolianum_min: Number,
    bifolianum_max: Number,
    /* Measurement */
    full_page_width_min: Number,
    full_page_width_max: Number,
    full_page_height_min: Number,
    full_page_height_max: Number,
    full_text_block_width_min: Number,
    full_text_block_width_max: Number,
    full_text_block_height_min: Number,
    full_text_block_height_max: Number,
    upper_margin_min: Number,
    upper_margin_max: Number,
    lower_margin_min: Number,
    lower_margin_max: Number,
    inner_margin_min: Number,
    inner_margin_max: Number,
    outer_margin_min: Number,
    outer_margin_max: Number,
    /* Palaeography */
    hand_number_min: Number,
    hand_number_max: Number,
    scripts_search: Array,
    diacritics_search: Array,
    punctuations_search: Array,
    paratexts_search: Array,
    decorations_search: Array,
    critical_symbols_search: Array,
    paginations_search: Array,
    quire_signatures_search: Array,
    /* Conservation and Analysis */
    storage_conditions_search: Array,
    analyses_search: Array,
    /* Provenance */
    scientifically_excavated: Number,
    ancient_provenances_search: Array,
    ancient_provenance_certainties_search: Array,
    transactions_search: Array,
    transaction_parties_search: Array,
    legal_classifications_search: Array,

    auth: Object,
});

const form = useForm({
    show_publication: props.show_publication,
    show_content: props.show_content,
    show_dating: props.show_dating,
    show_materiality: props.show_materiality,
    show_measurement: props.show_measurement,
    show_palaeography: props.show_palaeography,
    show_consanal: props.show_consanal,
    show_provenance: props.show_provenance,
    /* Search Fields */
    fulltext: props.fulltext,
    /* Publication */
    standard_name: props.standard_name,
    publication: props.publication,
    current_shelfmarks: props.current_shelfmarks,
    trismegistos_id: props.trismegistos_id,
    /* Content */
    title: props.title,
    ancient_author: props.ancient_author,
    genres: props.genres_search ? props.genres_search : [],
    tags: props.tags_search ? props.tags_search : [],
    /* Dating */
    earliest_date: props.earliest_date,
    latest_date: props.latest_date,
    exclusive_date: props.exclusive_date,
    dating_methods: props.dating_methods_search
        ? props.dating_methods_search
        : [],
    dating_certainties: props.dating_certainties_search
        ? props.dating_certainties_search
        : [],
    /* Materiality */
    materials: props.materials_search ? props.materials_search : [],
    inks: props.inks_search ? props.inks_search : [],
    covers: props.covers_search ? props.covers_search : [],
    quire_structures: props.quire_structures_search
        ? props.quire_structures_search
        : [],
    quirenum_min: props.quirenum_min,
    quirenum_max: props.quirenum_max,
    bifolianum_min: props.bifolianum_min,
    bifolianum_max: props.bifolianum_max,
    /* Measurement */
    full_page_width_min: props.full_page_width_min,
    full_page_width_max: props.full_page_width_max,
    full_page_height_min: props.full_page_height_min,
    full_page_height_max: props.full_page_height_max,
    full_text_block_width_min: props.full_text_block_width_min,
    full_text_block_width_max: props.full_text_block_width_max,
    full_text_block_height_min: props.full_text_block_height_min,
    full_text_block_height_max: props.full_text_block_height_max,
    upper_margin_min: props.upper_margin_min,
    upper_margin_max: props.upper_margin_max,
    lower_margin_min: props.lower_margin_min,
    lower_margin_max: props.lower_margin_max,
    inner_margin_min: props.inner_margin_min,
    inner_margin_max: props.inner_margin_max,
    outer_margin_min: props.outer_margin_min,
    outer_margin_max: props.outer_margin_max,
    /* Palaeography */
    hand_number_min: props.hand_number_min,
    hand_number_max: props.hand_number_max,
    scripts: props.scripts_search ? props.scripts_search : [],
    diacritics: props.diacritics_search ? props.diacritics_search : [],
    punctuations: props.punctuations_search ? props.punctuations_search : [],
    paratexts: props.paratexts_search ? props.paratexts_search : [],
    decorations: props.decorations_search ? props.decorations_search : [],
    critical_symbols: props.critical_symbols_search
        ? props.critical_symbols_search
        : [],
    paginations: props.paginations_search ? props.paginations_search : [],
    quire_signatures: props.quire_structures_search
        ? props.quire_structures_search
        : [],
    /* Conservation and Analysis */
    storage_conditions: props.storage_conditions_search
        ? props.storage_conditions_search
        : [],
    analyses: props.analyses_search ? props.analyses_search : [],
    /* Provenance */
    scientifically_excavated: props.scientifically_excavated,
    ancient_provenances: props.ancient_provenances_search
        ? props.ancient_provenances_search
        : [],
    ancient_provenance_certainties: props.ancient_provenance_certainties_search
        ? props.ancient_provenance_certainties_search
        : [],
    transactions: props.transactions_search ? props.transactions_search : [],
    transaction_parties: props.transaction_parties_search
        ? props.transaction_parties_search
        : [],
    legal_classifications: props.legal_classifications_search
        ? props.legal_classifications_search
        : [],
});

const yesnomenu = [
    { id: 1, name: "Yes" },
    { id: 2, name: "No" },
];

let edit = ref(props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0);

function sendsearch() {
    form.get("/codices", {
        queryStringArrayFormat: "indices",
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<style></style>
