<template>
    <div class="tab">
        <button
            :class="activetab == 'general' ? 'selected' : 'unselected'"
            @click="activetab = 'general'"
        >
            General Information
        </button>
        <button
            :class="activetab == 'codicology' ? 'selected' : 'unselected'"
            @click="activetab = 'codicology'"
        >
            Codicology
        </button>
        <button
            :class="activetab == 'conservation' ? 'selected' : 'unselected'"
            @click="activetab = 'conservation'"
        >
            Conservation and Analysis
        </button>
        <button
            :class="activetab == 'provenance' ? 'selected' : 'unselected'"
            @click="activetab = 'provenance'"
        >
            Provenance
        </button>
        <button
            :class="activetab == 'images' ? 'selected' : 'unselected'"
            @click="activetab = 'images'"
        >
            Images
        </button>
    </div>

    <div style="min-height: 95%; background-color: #eee">
        <div v-if="activetab == 'general'" class="showcodexcontainer">
            <fieldset class="showcodex_generalgrid">
                <legend class="showcodex_sectionheading">
                    Publication Info
                </legend>
                <div
                    v-if="
                        props.auth == null
                            ? 0
                            : props.auth.user.role.id >= 2
                            ? 1
                            : 0
                    "
                    class="show_published"
                >
                    <label>Published</label>
                    <div class="showcodex_bool">
                        {{ document.published ? "Yes" : "No" }}
                    </div>
                </div>

                <div class="show_standard">
                    <label>Standard name</label>
                    <div class="showcodex_string">
                        {{ document.standard_name }}
                    </div>
                </div>

                <div class="show_other">
                    <label>Other Names</label>
                    <div class="showcodex_text" v-html="document.other_names" />
                </div>

                <div class="show_publ">
                    <label>Publication</label>
                    <div
                        class="showcodex_string"
                        v-html="document.publication"
                    />
                </div>

                <div class="show_shelf">
                    <label>Current Shelfmarks</label>
                    <div class="showcodex_string">
                        {{ document.current_shelfmarks }}
                    </div>
                </div>

                <div class="show_trismegistos">
                    <label>Trismegistos ID</label>
                    <div class="showcodex_string_short">
                        {{ document.trismegistos_id }}
                    </div>
                </div>

                <div class="show_bibliography">
                    <label>Bibliography</label>
                    <div
                        class="showcodex_text"
                        v-html="document.bibliography"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_contentgrid">
                <legend class="showcodex_sectionheading">Content</legend>
                <div class="show_title">
                    <label>Title of Work</label>
                    <div class="showcodex_string">{{ document.title }}</div>
                </div>

                <div class="show_author">
                    <label>Author</label>
                    <div class="showcodex_string">
                        {{ document.ancient_author }}
                    </div>
                </div>

                <div class="show_genre">
                    <label>Genre</label>
                    <div
                        v-for="genre in genres"
                        :key="genre.id"
                        class="showcodex_multi_stack"
                    >
                        {{ genre.name }}
                    </div>
                </div>

                <div class="show_content">
                    <label>Content Tags</label>

                    <div class="showcodex_multi">
                        <div
                            v-for="tag in tags"
                            :key="tag.id"
                            class="showcodex_multi_line"
                        >
                            {{ tag.name }}
                        </div>
                    </div>
                </div>

                <div class="show_contdesc">
                    <label>Description of Content</label>
                    <div
                        class="showcodex_text"
                        v-html="document.content_description"
                    />
                </div>

                <div class="show_language">
                    <label>Language(s)</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="language in languages"
                            :key="language.id"
                            class="showcodex_multi_line"
                        >
                            {{ language.name }}
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="showcodex_date">
                <legend class="showcodex_sectionheading">Date</legend>
                <div class="show_early">
                    <label>Earliest Date (AD)</label>
                    <div class="showcodex_string">
                        {{ document.start_year }}
                    </div>
                </div>

                <div class="show_late">
                    <label>Latest Date (AD)</label>
                    <div class="showcodex_string">{{ document.end_year }}</div>
                </div>

                <div class="show_basis">
                    <label>Basis of Date</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="dating_method in dating_methods"
                            :key="dating_method.id"
                            class="showcodex_multi_line"
                        >
                            {{ dating_method.name }}
                        </div>
                    </div>
                </div>

                <div class="show_certainty">
                    <label>Certainty of Date</label>
                    <div class="showcodex_string">
                        {{
                            dating_certainty.length > 0
                                ? dating_certainty[0].name
                                : ""
                        }}
                    </div>
                </div>

                <div class="show_datecomm">
                    <label>Comments on Date</label>
                    <div
                        class="showcodex_text"
                        v-html="document.dating_comment"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_comments">
                <legend class="showcodex_sectionheading">Comments</legend>
                <div class="show_internal">
                    <label>Internal Comments</label>
                    <div
                        class="showcodex_text"
                        v-html="document.internal_comment"
                    />
                </div>

                <div class="show_general">
                    <label>General Comments</label>
                    <div
                        class="showcodex_text"
                        v-html="document.general_comment"
                    />
                </div>
            </fieldset>
        </div>

        <div v-if="activetab == 'codicology'" class="showcodexcontainer">
            <fieldset class="showcodex_materiality">
                <legend class="showcodex_sectionheading">Materiality</legend>
                <div class="show_material">
                    <label>Material</label>
                    <div class="showcodex_string_short">
                        {{ material.length > 0 ? material[0].name : "" }}
                    </div>
                </div>

                <div class="show_ink">
                    <label>Ink</label>
                    <div class="showcodex_string_short">
                        {{ ink.length > 0 ? ink[0].name : "" }}
                    </div>
                </div>

                <div class="show_cover">
                    <label>Cover</label>
                    <div class="showcodex_string_short">
                        {{ cover.length > 0 ? cover[0].name : "" }}
                    </div>
                </div>

                <div class="show_structure">
                    <label>Quire Structure</label>
                    <div class="showcodex_string_short">
                        {{
                            quire_structure.length > 0
                                ? quire_structure[0].name
                                : ""
                        }}
                    </div>
                </div>

                <div
                    v-if="document.quire_structure_id == 1"
                    class="show_numquire"
                >
                    <label for="bifolia">Number of Bifolia in the Quire</label>
                    <div class="showcodex_string_short">
                        {{ bifolia[0] }}
                    </div>
                </div>

                <div
                    v-if="document.quire_structure_id == 2"
                    class="show_numquire"
                >
                    <label>Number of Quires</label>
                    <div class="showcodex_string_short">
                        {{ document.quire_number }}
                    </div>
                </div>

                <div
                    v-if="document.quire_structure_id == 2"
                    class="show_quirenum"
                >
                    <label v-if="document.quire_number" :for="bifolia"
                        >Number of Bifolia in each Quire</label
                    >
                    <div class="showcodex_multi">
                        <div
                            class="showcodex_multi_line"
                            v-for="n in range(
                                1,
                                parseInt(document.quire_number)
                            )"
                            :key="n"
                        >
                            {{ n }}: {{ bifolia[n - 1] }}
                        </div>
                    </div>
                </div>

                <div
                    v-if="document.quire_structure_id == 3"
                    class="show_numquire"
                >
                    <label>Number of Quires</label>
                    <div class="showcodex_string_short">
                        {{ document.quire_number }}
                    </div>
                </div>

                <div
                    v-if="document.quire_structure_id == 3"
                    class="show_quirenum"
                >
                    <label :for="bifolia"
                        >Number of Bifolia in each Quire</label
                    >
                    <div class="showcodex_string_short">
                        {{ bifolia[0] }}
                    </div>
                </div>

                <div class="show_quirecomm">
                    <label>Comments on Quire(s)</label>
                    <div
                        class="showcodex_text"
                        v-html="document.quire_comment"
                    />
                </div>

                <div class="show_bindingdesc">
                    <label>Description of Binding</label>
                    <div
                        class="showcodex_text"
                        v-html="document.binding_description"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_measurements">
                <legend class="showcodex_sectionheading">Measurements</legend>
                <template v-if="!document.page_dimensions_known">
                    <div class="show_pagewidth">
                        <label>Fragmen Width (cm)</label>
                        <div class="showcodex_string_short">
                            {{ document.fragment_width }}
                        </div>
                    </div>
                    <div class="show_pageheight">
                        <label>Fragment Height (cm)</label>
                        <div class="showcodex_string_short">
                            {{ document.fragment_height }}
                        </div>
                    </div>
                </template>

                <template v-if="document.page_dimensions_known">
                    <div class="show_pagewidth">
                        <label>Full Page Width (cm)</label>
                        <div class="showcodex_string_short">
                            {{ document.full_page_width }}
                        </div>
                    </div>
                    <div class="show_pageheight">
                        <label>Full Page Height (cm)</label>
                        <div class="showcodex_string_short">
                            {{ document.full_page_height }}
                        </div>
                    </div>
                    <div class="show_pratio">
                        <EthRatio
                            :dividend="document.full_page_width"
                            :divisor="document.full_page_height"
                            >Ratio</EthRatio
                        >
                    </div>

                    <template v-if="document.textbox_size_stable">
                        <div class="show_uppmarg">
                            <label>Upper Margin (cm)</label>
                            <div class="showcodex_string_short">
                                {{ document.upper_margin }}
                            </div>
                        </div>
                        <div class="show_lowmarg">
                            <label>Lower Margin (cm)</label>
                            <div class="showcodex_string_short">
                                {{ document.lower_margin }}
                            </div>
                        </div>
                        <div class="show_ulratio">
                            <EthRatio
                                :dividend="document.upper_margin"
                                :divisor="document.lower_margin"
                                >Ratio</EthRatio
                            >
                        </div>

                        <div class="show_innmarg">
                            <label>Inner Margin (cm)</label>
                            <div class="showcodex_string_short">
                                {{ document.inner_margin }}
                            </div>
                        </div>
                        <div class="show_outmarg">
                            <label>Outer Margin</label>
                            <div class="showcodex_string_short">
                                {{ document.outer_margin }}
                            </div>
                        </div>
                        <div class="show_ioratio">
                            <EthRatio
                                :dividend="document.inner_margin"
                                :divisor="document.outer_margin"
                                >Ratio</EthRatio
                            >
                        </div>

                        <div class="show_blockwidth">
                            <label>Full Text Block Width (cm)</label>
                            <div class="showcodex_string_short">
                                {{ document.full_text_block_width }}
                            </div>
                        </div>
                        <div class="show_blockheight">
                            <label>Full Text Block Height (cm)</label>
                            <div class="showcodex_string_short">
                                {{ document.full_text_block_height }}
                            </div>
                        </div>
                        <div class="show_bratio">
                            <EthRatio
                                :dividend="document.full_text_block_width"
                                :divisor="document.full_text_block_height"
                                >Ratio</EthRatio
                            >
                        </div>
                    </template>
                </template>

                <div class="show_meascomm">
                    <label>Comments on Measurements</label>
                    <div
                        class="showcodex_text"
                        v-html="document.measurement_comment"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_textualfeatures">
                <legend class="showcodex_sectionheading">
                    Textual Features
                </legend>
                <div class="show_scripts">
                    <div v-if="checklanguage(1)">
                        <label>Greek Script(s)</label>
                        <div class="showcodex_multi">
                            <template
                                v-for="script in scripts"
                                :key="script.id"
                            >
                                <div
                                    v-if="script.language_id == 1"
                                    class="showcodex_multi_line"
                                >
                                    {{ script.name }}
                                </div>
                            </template>
                        </div>
                    </div>

                    <div v-if="checklanguage(2)" style="padding-top: 10px">
                        <label>Latin Script(s)</label>
                        <div class="showcodex_multi">
                            <template
                                v-for="script in scripts"
                                :key="script.id"
                            >
                                <div
                                    v-if="script.language_id == 2"
                                    class="showcodex_multi_line"
                                >
                                    {{ script.name }}
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="show_numhands">
                    <label>Number of Hands</label>
                    <div class="showcodex_string_short">
                        {{ document.hand_number }}
                    </div>
                </div>
                <div class="show_scriptdesc">
                    <label>Description of Scripts</label>
                    <div
                        class="showcodex_text"
                        v-html="document.script_description"
                    />
                </div>
                <div class="show_paratext">
                    <label>Paratexts</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="paratext in paratexts"
                            :key="paratext.id"
                            class="showcodex_multi_line"
                        >
                            {{ paratext.name }}
                        </div>
                    </div>
                </div>

                <div class="show_paradesc">
                    <label>Description of Paratexts</label>
                    <div
                        class="showcodex_text"
                        v-html="document.paratext_description"
                    />
                </div>

                <div class="show_punctuation">
                    <label>Punctuation</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="punctuation in punctuations"
                            :key="punctuation.id"
                            class="showcodex_multi_line"
                        >
                            {{ punctuation.name }}
                        </div>
                    </div>
                </div>
                <div class="show_diacritics">
                    <label>Diacritics</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="diacritic in diacritics"
                            :key="diacritic.id"
                            class="showcodex_multi_line"
                        >
                            {{ diacritic.name }}
                        </div>
                    </div>
                </div>
                <div class="show_diacritdesc">
                    <label>Description of Diacritics</label>
                    <div
                        class="showcodex_text"
                        v-html="document.diacritic_description"
                    />
                </div>
                <div class="show_critical">
                    <label>Critical Symbols</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="critical_symbol in critical_symbols"
                            :key="critical_symbol.id"
                            class="showcodex_multi_line"
                        >
                            {{ critical_symbol.name }}
                        </div>
                    </div>
                </div>

                <div class="show_decorative">
                    <label>Decorative Symbols</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="decoration in decorations"
                            :key="decoration.id"
                            class="showcodex_multi_line"
                        >
                            {{ decoration.name }}
                        </div>
                    </div>
                </div>

                <div class="show_decodesc">
                    <label>Description of Decorative Symbols</label>
                    <div
                        class="showcodex_text"
                        v-html="document.decoration_description"
                    />
                </div>

                <div class="show_pagination">
                    <label>Pagination</label>
                    <div class="showcodex_string_short">
                        {{ pagination.length > 0 ? pagination[0].name : "" }}
                    </div>
                </div>

                <div class="show_quiresig">
                    <label>Quire Signatures</label>
                    <div class="showcodex_string_short">
                        {{
                            quire_signature.length > 0
                                ? quire_signature[0].name
                                : ""
                        }}
                    </div>
                </div>
            </fieldset>
        </div>

        <div v-if="activetab == 'conservation'" class="showcodexcontainer">
            <fieldset class="showcodex_conservanalys">
                <legend class="showcodex_sectionheading">
                    Conservation and Analysis
                </legend>
                <div class="show_storage">
                    <label>Storage Condition</label>
                    <div class="showcodex_string">
                        {{
                            storage_condition.length > 0
                                ? storage_condition[0].name
                                : ""
                        }}
                    </div>
                </div>
                <div class="show_conshist">
                    <label>Conservation History</label>
                    <div
                        class="showcodex_text"
                        v-html="document.conservation_history"
                    />
                </div>
                <div class="show_scientific">
                    <label>Scientific Analyses</label>
                    <div
                        v-for="analysis in analyses"
                        :key="analysis.id"
                        class="showcodex_multi_stack"
                    >
                        {{ analysis.name }}
                    </div>
                </div>

                <div class="analyscomm">
                    <label>Comments on Analysis</label>
                    <div
                        class="showcodex_text"
                        v-html="document.analyses_comment"
                    />
                </div>
            </fieldset>
        </div>

        <div v-if="activetab == 'provenance'" class="showcodexcontainer">
            <fieldset class="showcodex_provenance">
                <legend class="showcodex_sectionheading">Provenance</legend>
                <div class="show_sciex">
                    <label :for="scientifically_excavated"
                        >Scientifically Excavated</label
                    >
                    <div class="showcodex_bool">
                        {{ document.scientifically_excavated ? "Yes" : "No" }}
                    </div>
                </div>

                <div class="show_excomm">
                    <label>Comments on Excavation</label>
                    <div
                        class="showcodex_text"
                        v-html="document.excavation_comment"
                    />
                </div>

                <div class="show_anciprov">
                    <label>Ancient Provenance</label>
                    <div class="showcodex_string">
                        {{
                            ancient_provenance.length > 0
                                ? ancient_provenance[0].name
                                : ""
                        }}
                    </div>
                </div>

                <div class="show_ancicert">
                    <label>Certainty of Ancient Provenance</label>
                    <div class="showcodex_string_short">
                        {{
                            ancient_provenance_certainty.length > 0
                                ? ancient_provenance_certainty[0].name
                                : ""
                        }}
                    </div>
                </div>

                <div class="show_ancicomm">
                    <label>Comments on Ancient Provenance</label>
                    <div
                        class="showcodex_text"
                        v-html="document.ancient_provenance_comment"
                    />
                </div>

                <!--
                        
            <EthInput
                input_type="multi_choice"
                input_id="modern_collection"
                :choices="modern_collections_all"
                v-model="form.modern_collections"
            >
                Modern Collections
            </EthInput>
-->

                <div class="show_legalclass">
                    <label>Legal Classification</label>
                    <div class="showcodex_string">
                        {{
                            legal_classification.length > 0
                                ? legal_classification[0].name
                                : ""
                        }}
                    </div>
                </div>

                <div class="show_legalcomm">
                    <label>Comments on Legal Classification</label>
                    <div
                        class="showcodex_text"
                        v-html="document.legal_classification_explanation"
                    />
                </div>

                <div class="show_transactions">
                    <label>Transactions</label>
                    <div
                        v-for="purchase in purchases"
                        :key="purchase.id"
                        class="showcodex_multi_stack"
                    >
                        <button @click="seltrans = purchase">Info</button>
                        {{ purchase.name }}
                    </div>
                </div>

                <div class="show_transinfo">
                    <label>Transaction Information</label>
                    <div class="showcodex_text">
                        <div v-if="Object.keys(seltrans).length !== 0">
                            <h1>{{ seltrans.name }}</h1>
                            <p>
                                Date:
                                <span v-if="seltrans.year">{{
                                    seltrans.year
                                }}</span>
                                <span v-if="seltrans.month"
                                    >-{{ seltrans.month }}</span
                                >
                                <span v-if="seltrans.day"
                                    >-{{ seltrans.day }}</span
                                >
                            </p>
                            <span v-if="seltrans.description">
                                <p>Description: {{ seltrans.description }}</p>
                            </span>
                            <h2>Codices in Transaction</h2>
                            <p
                                v-for="codex in seltrans.documents"
                                :key="codex.id"
                            >
                                <Link
                                    as="button"
                                    type="button"
                                    :href="'/codex_show/' + codex.id"
                                    style="
                                        margin-right: 5px;
                                        padding: 2px 3px
                                        font-size: 12px;
                                    "
                                    >Show
                                </Link>
                                {{ codex.standard_name }}
                            </p>
                            <h2>Parties to the Transaction</h2>
                            <p
                                v-for="party in seltrans.purchase_parties"
                                :key="party.id"
                                :title="party.description"
                            >
                                {{ party.name }}
                            </p>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div v-if="activetab == 'images'" class="showcodexcontainer">
            <fieldset class="showcodex_images">
                <legend class="showcodex_sectionheading">Images</legend>
                <div class="show_img">
                    <label>Image Information</label>
                    <div class="showcodex_text" v-html="document.images_info" />
                </div>

                <div class="images_rows">
                    <div
                        class="box"
                        v-for="(image, index) in images"
                        :key="image.id"
                    >
                        <a :href="'/storage/' + image.filename" target="_blank">
                            <img
                                :src="'/storage/' + image.filename"
                                height="360"
                            />
                        </a>
                        <label
                            class="labelpadding"
                            :for="'image_description_' + index"
                            >Description:
                        </label>
                        <div
                            class="showcodex_text"
                            v-html="image.description"
                        />
                        <label>Source</label>
                        <div class="showcodex_string" v-html="image.source" />

                        <label>License</label>
                        <div class="showcodex_string">
                            {{ image.license ? image.license.name : "" }}
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, onMounted } from "vue";
import EthRatio from "../Components/EthRatio.vue";

const props = defineProps({
    analyses: Array,
    ancient_provenance: Array,
    ancient_provenance_certainty: Array,
    cover: Array,
    critical_symbols: Array,
    dating_certainty: Array,
    dating_methods: Array,
    decorations: Array,
    diacritics: Array,
    document: Object,
    genres: Array,
    images: Array,
    ink: Array,
    languages: Array,
    legal_classification: Array,
    material: Array,
    modern_collections: Array,
    newimages: Array,
    pagination: Array,
    paratexts: Array,
    punctuations: Array,
    purchases: Array,
    quire_signature: Array,
    quire_structure: Array,
    scripts: Array,
    storage_condition: Array,
    tags: Array,
    auth: Object,
});

const bifolia = props.document.bifolia
    ? JSON.parse(props.document.bifolia)
    : [];
if (!bifolia) {
    bifolia[0] = 0;
}

const loadImages = ref(null);

let activetab = ref("general");
let seltrans = ref({});

function checklanguage(lang) {
    let found = false;
    for (const s of props.scripts) {
        if (s.language_id == lang) {
            found = true;
        }
    }
    return found;
}

function range(start, end) {
    var foo = [];
    for (var i = start; i <= end; i++) {
        foo.push(i);
    }
    return foo;
}
</script>

<style>
.tab {
    display: flex;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    width: 100%;
    justify-content: stretch;
}

.tab button {
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 5px;
    font-size: 18px;
    width: 100%;
    transition: 50ms;
}

button.unselected {
    background-color: rgb(225, 225, 228);
}

button.unselected:hover {
    background-color: rgb(186, 189, 185);
}

button.selected:hover {
    background-color: rgb(77, 87, 66);
}

button.selected {
    background-color: rgb(119, 128, 111);
}

.showcodexcontainer {
    display: flex;
    background-color: #eee;
    margin-top: 4px;
    padding: 20px;
    font-family: sans-serif;
    font-size: 16px;
    flex-direction: column;
    width: 100%;
    height: auto;
    justify-content: center;
}

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
}
.show_cover {
    grid-area: cover;
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
        "ink bindingdesc"
        "cover bindingdesc"
        "structure quirecomm"
        "numquire quirecomm"
        "quirenum quirecomm";
    gap: 20px;
    width: 99%;
    align-self: center;
    background-color: #ccc;
    padding: 25px;
    border-radius: 10px;
    margin-top: 15px;
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
}
.show_uppmarg {
    grid-area: uppmarg;
}
.show_lowmarg {
    grid-area: lowmarg;
}
.show_ulratio {
    grid-area: ulratio;
}
.show_innmarg {
    grid-area: innmarg;
}
.show_outmarg {
    grid-area: outmarg;
}
.show_ioratio {
    grid-area: ioratio;
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

.showcodex_textualfeatures {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-template-rows: auto;
    grid-template-areas:
        "scripts numhands diacritics diacritics"
        "scriptdesc scriptdesc diacritdesc diacritdesc"
        "paratext paratext decorative decorative"
        "paradesc paradesc decodesc decodesc"
        "punctuation critical pagination quiresig";
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
    margin-top: 15px;
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
    margin-top: 15px;
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
    margin-top: 15px;
}

.box {
    padding: 10px;
    background-color: #bbb;
    margin: 5px;
    display: flex;
    flex-direction: column;
    width: fit-content;
}

.images_rows {
    display: inline-flex;
    flex-direction: row;
    flex-wrap: wrap;
}
.labelpadding {
    padding-top: 15px;
    padding-right: 15px;
}

.showcodex_sectionheading {
    background-color: #fff;
    border-style: solid;
    border-width: 1px;
    border-radius: 10px;
    padding: 5px 15px;
    font-size: 18px;
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
</style>
