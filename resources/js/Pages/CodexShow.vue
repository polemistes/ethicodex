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

    <div v-if="activetab == 'general'" class="showcodexcontainer">
        <div class="showcodex_generalgrid">
            <div class="show_published">
                <label>Published</label>
                <p>{{ document.published ? "Yes" : "No" }}</p>
            </div>

            <div class="show_standard">
                <label>Standard name</label>
                <p>{{ document.standard_name }}</p>
            </div>

            <div class="show_other">
                <label>Other Names</label>
                <p>{{ document.other_names }}</p>
            </div>

            <div class="show_publ">
                <label>Publication</label>
                <p>{{ document.publication }}</p>
            </div>

            <div class="show_shelf">
                <label>Current Shelfmarks</label>
                <p>{{ document.current_shelfmark }}</p>
            </div>

            <div class="show_trismegistos">
                <label>Trismegistos ID</label>
                <p>{{ document.trismegistos_id }}</p>
            </div>

            <div class="show_bibliography">
                <label>Bibliography</label>
                <div>{{ document.bibliography }}</div>
            </div>
        </div>

        <!--
            <div class="showcodex_contentgrid">
                <div class="show_title">
                    <EthInput
                        input_type="text"
                        input_id="title"
                        v-model="form.title"
                        >Title of Work</EthInput
                    >
                </div>

                <div class="show_author">
                    <EthInput
                        input_type="text"
                        input_id="ancient_author"
                        v-model="form.ancient_author"
                        >Author</EthInput
                    >
                </div>

                <div class="show_genre">
                    <EthInput
                        input_type="multi_choice"
                        input_id="genres"
                        :choices="genres_all"
                        v-model="form.genres"
                    >
                        Genre
                    </EthInput>
                </div>

                <div class="show_content">
                    <EthInput
                        input_type="multi_choice"
                        input_id="tags"
                        :choices="tags_all"
                        v-model="form.tags"
                    >
                        Content Tags
                    </EthInput>
                </div>

                <div class="show_contdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="content_description"
                        v-model="form.content_description"
                        >Description of Content</EthInput
                    >
                </div>

                <div class="show_language">
                    <EthInput
                        input_type="multi_choice"
                        input_id="languages"
                        :choices="languages_all"
                        v-model="form.languages"
                    >
                        Languages
                    </EthInput>
                </div>
            </div>

            <div class="showcodex_date">
                <div class="show_early">
                    <EthInput
                        input_type="year"
                        input_id="start_year"
                        v-model="form.start_year"
                        >Earliest Date</EthInput
                    >
                </div>

                <div class="show_late">
                    <EthInput
                        input_type="year"
                        input_id="end_year"
                        v-model="form.end_year"
                        >Latest Date</EthInput
                    >
                </div>

                <div class="show_basis">
                    <EthInput
                        input_type="multi_choice"
                        input_id="dating_methods"
                        :choices="dating_methods_all"
                        v-model="form.dating_methods"
                    >
                        Basis of Date
                    </EthInput>
                </div>

                <div class="show_certainty">
                    <EthInput
                        input_type="single_choice"
                        input_id="dating_certainty"
                        :choices="dating_certainties"
                        v-model="form.dating_certainty_id"
                    >
                        Certainty of Date
                    </EthInput>
                </div>

                <div class="show_datecomm">
                    <EthInput
                        input_type="textarea"
                        input_id="dating_comment"
                        v-model="form.dating_comment"
                        >Comments on Date</EthInput
                    >
                </div>
            </div>

            <div class="showcodex_comments">
                <div class="show_internal">
                    <EthInput
                        input_type="textarea"
                        input_id="internal_comment"
                        v-model="form.internal_comment"
                        >Internal Comments</EthInput
                    >
                </div>

                <div class="show_general">
                    <EthInput
                        input_type="textarea"
                        input_id="general_comment"
                        v-model="form.general_comment"
                        >General Comments</EthInput
                    >
                </div>
            </div>

            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
-->
    </div>
    <!--
        <fieldset v-if="activetab == 'codicology'" class="showcodexcontainer">
            <div class="showcodex_materiality">
                <div class="show_material">
                    <EthInput
                        input_type="single_choice"
                        input_id="material"
                        :choices="materials"
                        v-model="form.material_id"
                    >
                        Material
                    </EthInput>
                </div>

                <div class="show_ink">
                    <EthInput
                        input_type="single_choice"
                        input_id="ink"
                        :choices="inks"
                        v-model="form.ink_id"
                    >
                        Ink
                    </EthInput>
                </div>

                <div class="show_cover">
                    <EthInput
                        input_type="single_choice"
                        input_id="cover"
                        :choices="covers"
                        v-model="form.cover_id"
                    >
                        Cover
                    </EthInput>
                </div>

                <div class="show_structure">
                    <EthInput
                        input_type="single_choice"
                        input_id="quire_structure"
                        :choices="quire_structures"
                        v-model="form.quire_structure_id"
                    >
                        Quire Structure
                    </EthInput>
                </div>

                <div v-if="form.quire_structure_id == 1" class="show_numquire">
                    <label :for="bifolia">Number of Bifolia in the Quire</label>
                    <div>
                        <p>
                            <input
                                type="number"
                                id="bifolia"
                                min="1"
                                required
                                v-model="form.bifolia[0]"
                            />
                        </p>
                    </div>
                </div>

                <div v-if="form.quire_structure_id == 2" class="show_numquire">
                    <EthInput
                        input_type="number"
                        input_id="quire_number"
                        v-model="form.quire_number"
                        >Number of Quires</EthInput
                    >
                    <div class="show_quirenum">
                        <label v-if="form.quire_number" :for="bifolia"
                            >Number of Bifolia in each Quire</label
                        >
                        <div
                            v-for="n in range(1, parseInt(form.quire_number))"
                            :key="n"
                        >
                            <p>
                                {{ n }}:
                                <input
                                    type="number"
                                    min="1"
                                    required
                                    v-model="form.bifolia[n - 1]"
                                />
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="form.quire_structure_id == 3" class="show_numquire">
                    <EthInput
                        input_type="number"
                        input_id="quire_number"
                        v-model="form.quire_number"
                        >Number of Quires</EthInput
                    >
                </div>
                <div v-if="form.quire_structure_id == 3" class="show_quirenum">
                    <label :for="bifolia"
                        >Number of Bifolia in each Quire</label
                    >
                    <div>
                        <p>
                            <input
                                type="number"
                                id="bifolia"
                                min="1"
                                required
                                v-model="form.bifolia[0]"
                            />
                        </p>
                    </div>
                </div>

                <div class="show_quirecomm">
                    <EthInput
                        input_type="textarea"
                        input_id="quire_comment"
                        v-model="form.quire_comment"
                        >Comments on Quire(s)</EthInput
                    >
                </div>

                <div class="show_bindingdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="binding_description"
                        v-model="form.binding_description"
                        >Binding Description</EthInput
                    >
                </div>
            </div>

            <div class="showcodex_measurements">
                <div class="show_dimknown">
                    <label :for="page_dimensions_known"
                        >Page Dimensions Known</label
                    >
                    <input
                        :id="page_dimensions_known"
                        type="checkbox"
                        v-model="form.page_dimensions_known"
                    />
                </div>

                <template v-if="!form.page_dimensions_known">
                    <div class="show_pagewidth">
                        <EthInput
                            input_type="text"
                            input_id="fragment_width"
                            v-model="form.fragment_width"
                            >Fragment Width (cm)</EthInput
                        >
                    </div>
                    <div class="show_pageheight">
                        <EthInput
                            input_type="text"
                            input_id="fragment_height"
                            v-model="form.fragment_height"
                            >Fragment Height (cm)</EthInput
                        >
                    </div>
                </template>

                <template v-if="form.page_dimensions_known">
                    <div class="show_pagewidth">
                        <EthInput
                            input_type="text"
                            input_id="full_page_width"
                            v-model="form.full_page_width"
                            >Full Page Width (cm)</EthInput
                        >
                    </div>
                    <div class="show_pageheight">
                        <EthInput
                            input_type="text"
                            input_id="full_page_height"
                            v-model="form.full_page_height"
                            >Full Page Height (cm)</EthInput
                        >
                    </div>
                    <div class="show_pratio">
                        <EthRatio
                            :dividend="form.full_page_width"
                            :divisor="form.full_page_height"
                            >Full Page Width/Height Ratio</EthRatio
                        >
                    </div>

                    <div class="show_stable">
                        <div>
                            <label :for="textbox_size_stable"
                                >Size of Textbox is Stable</label
                            >
                            <input
                                :id="textbox_size_stable"
                                type="checkbox"
                                v-model="form.textbox_size_stable"
                            />
                        </div>
                    </div>

                    <template v-if="form.textbox_size_stable">
                        <div class="show_uppmarg">
                            <EthInput
                                input_type="text"
                                input_id="upper_margin"
                                v-model="form.upper_margin"
                                >Upper Margin (cm)</EthInput
                            >
                        </div>
                        <div class="show_lowmarg">
                            <EthInput
                                input_type="text"
                                input_id="lower_margin"
                                v-model="form.lower_margin"
                                >Lower Margin (cm)</EthInput
                            >
                        </div>
                        <div class="show_ulratio">
                            <EthRatio
                                :dividend="form.upper_margin"
                                :divisor="form.lower_margin"
                                >Upper/Lower Margin Ratio</EthRatio
                            >
                        </div>

                        <div class="show_innmarg">
                            <EthInput
                                input_type="text"
                                input_id="inner_margin"
                                v-model="form.inner_margin"
                                >Inner Margin (cm)</EthInput
                            >
                        </div>
                        <div class="show_outmarg">
                            <EthInput
                                input_type="text"
                                input_id="outer_margin"
                                v-model="form.outer_margin"
                                >Outer Margin (cm)</EthInput
                            >
                        </div>
                        <div class="show_ioratio">
                            <EthRatio
                                :dividend="form.inner_margin"
                                :divisor="form.outer_margin"
                                >Inner/Outer Margin Ratio</EthRatio
                            >
                        </div>

                        <div class="show_blockwidth">
                            <EthInput
                                input_type="text"
                                input_id="full_text_block_width"
                                v-model="form.full_text_block_width"
                                >Full Text Block Width (cm)</EthInput
                            >
                        </div>
                        <div class="show_blockheight">
                            <EthInput
                                input_type="text"
                                input_id="full_text_block_height"
                                v-model="form.full_text_block_height"
                                >Full Text Block Height (cm)</EthInput
                            >
                        </div>
                        <div class="show_bratio">
                            <EthRatio
                                :dividend="form.full_text_block_width"
                                :divisor="form.full_text_block_height"
                                >Full Text Block Width/Height Ratio</EthRatio
                            >
                        </div>
                    </template>
                </template>

                <div class="show_meascomm">
                    <EthInput
                        input_type="textarea"
                        input_id="measurement_comment"
                        v-model="form.measurement_comment"
                        >Comments on Measurements</EthInput
                    >
                </div>
            </div>

            <div class="showcodex_textualfeatures">
                <div class="show_scripts">
                    <EthInput
                        input_type="multi_choice_scripts"
                        input_id="scripts"
                        :choices="scripts_all"
                        v-model="form.scripts"
                    >
                        Scripts
                    </EthInput>
                </div>
                <div class="show_numhands">
                    <EthInput
                        input_type="number"
                        input_id="hand_number"
                        v-model="form.hand_number"
                        >Number of Hands</EthInput
                    >
                </div>
                <div class="show_scriptdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="script_description"
                        v-model="form.script_description"
                        >Description of Scripts</EthInput
                    >
                </div>
                <div class="show_paratext">
                    <EthInput
                        input_type="multi_choice"
                        input_id="paratexts"
                        :choices="paratexts_all"
                        v-model="form.paratexts"
                    >
                        Paratexts
                    </EthInput>
                </div>

                <div class="show_paradesc">
                    <EthInput
                        input_type="textarea"
                        input_id="paratext_description"
                        v-model="form.paratext_description"
                        >Description of Paratexts</EthInput
                    >
                </div>
                <div class="show_punctuation">
                    <EthInput
                        input_type="multi_choice"
                        input_id="punctuations"
                        :choices="punctuations_all"
                        v-model="form.punctuations"
                    >
                        Punctuation
                    </EthInput>
                </div>
                <div class="show_diacritics">
                    <EthInput
                        input_type="multi_choice"
                        input_id="diacritics"
                        :choices="diacritics_all"
                        v-model="form.diacritics"
                    >
                        Diacritics
                    </EthInput>
                </div>
                <div class="show_diacritdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="diacritic_description"
                        v-model="form.diacritic_description"
                        >Description of Diacritics</EthInput
                    >
                </div>
                <div class="show_critical">
                    <EthInput
                        input_type="multi_choice"
                        input_id="critical_symbols"
                        :choices="critical_symbols_all"
                        v-model="form.critical_symbols"
                    >
                        Critical Symbols
                    </EthInput>
                </div>

                <div class="show_decorative">
                    <EthInput
                        input_type="multi_choice"
                        input_id="decorations"
                        :choices="decorations_all"
                        v-model="form.decorations"
                    >
                        Decorative Symbols
                    </EthInput>
                </div>

                <div class="show_decodesc">
                    <EthInput
                        input_type="textarea"
                        input_id="decoration_description"
                        v-model="form.decoration_description"
                        >Description of Decorative Symbols</EthInput
                    >
                </div>

                <div class="show_pagination">
                    <EthInput
                        input_type="single_choice"
                        input_id="pagination"
                        :choices="paginations"
                        v-model="form.pagination_id"
                    >
                        Pagination
                    </EthInput>
                </div>

                <div class="show_quiresig">
                    <EthInput
                        input_type="single_choice"
                        input_id="quire_signature"
                        :choices="quire_signatures"
                        v-model="form.quire_signature_id"
                    >
                        Quire Signatures
                    </EthInput>
                </div>
            </div>

            <button class="submitbutton" @click="submit">
                Store All Changes
            </button>
        </fieldset>

        <fieldset v-if="activetab == 'conservation'" class="showcodexcontainer">
            <div class="showcodex_conservanalys">
                <div class="show_storage">
                    <EthInput
                        input_type="single_choice"
                        input_id="storage_condition"
                        :choices="storage_conditions"
                        v-model="form.storage_condition_id"
                    >
                        Storage Condition
                    </EthInput>
                </div>
                <div class="show_conshist">
                    <EthInput
                        input_type="textarea"
                        input_id="conservation_history"
                        v-model="form.conservation_history"
                        >Conservation History</EthInput
                    >
                </div>
                <div class="show_scientific">
                    <EthInput
                        input_type="multi_choice"
                        input_id="analyses"
                        :choices="analyses_all"
                        v-model="form.analyses"
                    >
                        Scientific Analysis
                    </EthInput>
                </div>
                <div class="analyscomm">
                    <EthInput
                        input_type="textarea"
                        input_id="analyses_comment"
                        v-model="form.analyses_comment"
                        >Comments on Analysis</EthInput
                    >
                </div>
            </div>

            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
        </fieldset>

        <fieldset v-if="activetab == 'provenance'" class="showcodexcontainer">
            <div class="showcodex_provenance">
                <div class="show_sciex">
                    <label :for="scientifically_excavated"
                        >Scientifically Excavated</label
                    >
                    <input
                        :id="scientifically_excavated"
                        type="checkbox"
                        v-model="form.scientifically_excavated"
                    />
                </div>

                <div class="show_excomm">
                    <EthInput
                        input_type="textarea"
                        input_id="excavation_comment"
                        v-model="form.excavation_comment"
                    >
                        Comments on Excavation
                    </EthInput>
                </div>

                <div class="show_anciprov">
                    <EthInput
                        input_type="single_choice"
                        input_id="ancient_provenance"
                        :choices="ancient_provenances"
                        v-model="form.ancient_provenance_id"
                    >
                        Ancient Provenance
                    </EthInput>
                </div>

                <div class="show_ancicert">
                    <EthInput
                        input_type="single_choice"
                        input_id="ancient_provenance_certainty"
                        :choices="ancient_provenance_certainties"
                        v-model="form.ancient_provenance_certainty_id"
                    >
                        Certainty of A. Provenance
                    </EthInput>
                </div>

                <div class="show_ancicomm">
                    <EthInput
                        input_type="textarea"
                        input_id="ancient_provenance_comment"
                        v-model="form.ancient_provenance_comment"
                    >
                        Comments on Ancient Provenance
                    </EthInput>
                </div>
-->
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
    <!--
                <div class="show_legalclass">
                    <EthInput
                        input_type="legal_choice"
                        input_id="legal_classification"
                        :choices="legal_classifications"
                        v-model="form.legal_classification_id"
                    >
                        Legal Classification
                    </EthInput>
                </div>

                <div class="show_legalcomm">
                    <EthInput
                        input_type="textarea"
                        input_id="legal_classification_explanation"
                        v-model="form.legal_classification_explanation"
                        >Comments on Legal Classification</EthInput
                    >
                </div>

                <div class="show_transactions">
                    <EthInput
                        input_type="multi_choice"
                        input_id="purchases"
                        :choices="purchases_all"
                        v-model="form.purchases"
                    >
                        Transactions
                    </EthInput>
                </div>
            </div>
            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
        </fieldset>

        <fieldset v-if="activetab == 'images'" class="showcodexcontainer">
            <div class="showcodex_images">
                <div class="show_img">
                    <EthInput
                        input_type="textarea"
                        input_id="images_info"
                        v-model="form.images_info"
                        >Image Information</EthInput
                    >
                </div>
            </div>

            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>

            <label class="labelpadding" for="images">Upload Images</label>
            <input
                type="file"
                @change="addimages($event.target.files)"
                multiple
                ref="loadImages"
            />

            <div>
                <div
                    class="box"
                    v-for="(image, index) in form.images"
                    :key="image.id"
                >
                    <a :href="'/storage/' + image.filename" target="_blank">
                        <img :src="'/storage/' + image.filename" height="400" />
                    </a>
                    <button @click.prevent="delimage(image)">Delete</button>
                    <label
                        class="labelpadding"
                        :for="'image_description_' + index"
                        >Description:
                    </label>
                    <textarea
                        :id="'image_description_' + index"
                        v-model="image.description"
                    />

                    <EthInput
                        input_type="text"
                        input_id="image_source"
                        v-model="image.source"
                    >
                        Source
                    </EthInput>

                    <EthInput
                        input_type="single_choice"
                        input_id="license"
                        :choices="licenses"
                        v-model="image.license_id"
                    >
                        License:
                    </EthInput>
                </div>
            </div>
        </fieldset>
        -->
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, onMounted } from "vue";
import EthRatio from "../Components/EthRatio.vue";

const props = defineProps({
    analyses: Array,
    ancient_provenance: Object,
    ancient_provenance_certainty: Object,
    cover: Object,
    critical_symbols: Array,
    dating_certainty: Object,
    dating_methods: Array,
    decorations: Array,
    diacritics: Array,
    document: Object,
    genres: Array,
    images: Array,
    ink: Object,
    languages: Array,
    legal_classification: Object,
    licenses: Array,
    material: Object,
    modern_collections: Array,
    newimages: Array,
    pagination: Object,
    paratexts: Array,
    published: Boolean,
    punctuations: Array,
    purchases: Array,
    quire_signature: Array,
    quire_structure: Object,
    scripts: Array,
    storage_condition: Object,
    tags: Array,
});

const loadImages = ref(null);

let activetab = ref("general");

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

.submitbutton {
    font-size: 20px;
    width: auto;
    height: auto;
    padding: 20px;
    border-radius: 15px;
    align-self: center;
    margin: 20px;
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
    justify-content: center;
}

.show_published {
    grid-area: published;
    align-self: start;
}
.show_trismegistos {
    grid-area: trismegistos;
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
}
.show_genre {
    grid-area: genre;
}
.show_content {
    grid-area: cont;
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
    grid-template-areas: "general internal";
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
}
.show_scriptdesc {
    grid-area: scriptdesc;
}
.show_pagination {
    grid-area: pagination;
}
.show_paratext {
    grid-area: paratext;
}
.show_paradesc {
    grid-area: paradesc;
}
.show_punctuation {
    grid-area: punctuation;
}
.show_diacritics {
    grid-area: diacritics;
}
.show_diacritdesc {
    grid-area: diacritdesc;
}
.show_critical {
    grid-area: critical;
}
.show_decorative {
    grid-area: decorative;
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

.showcodex_provenance {
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
    background-color: lightslategrey;
    margin: 5px;
    display: flex;
    flex-direction: column;
}

.labelpadding {
    padding-top: 15px;
    padding-right: 15px;
}
</style>
