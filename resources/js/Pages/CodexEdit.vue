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

    <form
        @submit.prevent="submit"
        style="min-height: 95%; background-color: #eee"
    >
        <div v-if="activetab == 'general'" class="editcodexcontainer">
            <input type="hidden" input_id="id" v-model="form.id" />

            <fieldset class="editcodex_generalgrid">
                <legend class="editcodex_sectionheading">
                    Publication Info
                </legend>
                <div class="edit_published">
                    <label :for="published">Published</label>
                    <input
                        :id="published"
                        type="checkbox"
                        v-model="form.published"
                    />
                </div>

                <div class="edit_standard">
                    <EthInput
                        input_type="text"
                        input_id="standard_name"
                        v-model="form.standard_name"
                    >
                        Standard Name
                    </EthInput>
                </div>

                <div class="edit_other">
                    <EthInput
                        class="edit_other"
                        input_type="textarea"
                        input_id="other_names"
                        v-model="form.other_names"
                        >Other Names</EthInput
                    >
                </div>

                <div class="edit_publ">
                    <EthInput
                        input_type="text"
                        input_id="publication"
                        v-model="form.publication"
                        >Publication</EthInput
                    >
                </div>

                <div class="edit_shelf">
                    <EthInput
                        input_type="text"
                        input_id="current_shelfmarks"
                        v-model="form.current_shelfmarks"
                        >Current Shelfmarks</EthInput
                    >
                </div>

                <div class="edit_trismegistos">
                    <EthInput
                        input_type="text"
                        input_id="trismegistos_id"
                        v-model="form.trismegistos_id"
                        >Trismegistos ID</EthInput
                    >
                </div>

                <div class="edit_bibliography">
                    <EthInput
                        input_type="textarea"
                        input_id="bibliography"
                        v-model="form.bibliography"
                        >Bibliography</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_contentgrid">
                <legend class="editcodex_sectionheading">Content</legend>
                <div class="edit_title">
                    <EthInput
                        input_type="text"
                        input_id="title"
                        v-model="form.title"
                        >Title of Work</EthInput
                    >
                </div>

                <div class="edit_author">
                    <EthInput
                        input_type="text"
                        input_id="ancient_author"
                        v-model="form.ancient_author"
                        >Author</EthInput
                    >
                </div>

                <div class="edit_genre">
                    <EthInput
                        input_type="multi_choice"
                        input_id="genres"
                        :choices="genres_all"
                        v-model="form.genres"
                    >
                        Genre
                    </EthInput>
                </div>

                <div class="edit_content">
                    <EthInput
                        input_type="multi_choice"
                        input_id="tags"
                        :choices="tags_all"
                        v-model="form.tags"
                    >
                        Content Tags
                    </EthInput>
                </div>

                <div class="edit_contdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="content_description"
                        v-model="form.content_description"
                        >Description of Content</EthInput
                    >
                </div>

                <div class="edit_language">
                    <EthInput
                        input_type="multi_choice"
                        input_id="languages"
                        :choices="languages_all"
                        v-model="form.languages"
                    >
                        Languages
                    </EthInput>
                </div>
            </fieldset>

            <fieldset class="editcodex_date">
                <legend class="editcodex_sectionheading">Date</legend>
                <div class="edit_early">
                    <EthInput
                        input_type="year"
                        input_id="start_year"
                        v-model="form.start_year"
                        >Earliest Date</EthInput
                    >
                </div>

                <div class="edit_late">
                    <EthInput
                        input_type="year"
                        input_id="end_year"
                        v-model="form.end_year"
                        >Latest Date</EthInput
                    >
                </div>

                <div class="edit_basis">
                    <EthInput
                        input_type="multi_choice"
                        input_id="dating_methods"
                        :choices="dating_methods_all"
                        v-model="form.dating_methods"
                    >
                        Basis of Date
                    </EthInput>
                </div>

                <div class="edit_certainty">
                    <EthInput
                        input_type="single_choice"
                        input_id="dating_certainty"
                        :choices="dating_certainties"
                        v-model="form.dating_certainty_id"
                    >
                        Certainty of Date
                    </EthInput>
                </div>

                <div class="edit_datecomm">
                    <EthInput
                        input_type="textarea"
                        input_id="dating_comment"
                        v-model="form.dating_comment"
                        >Comments on Date</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_comments">
                <legend class="editcodex_sectionheading">Comments</legend>
                <div class="edit_internal">
                    <EthInput
                        input_type="textarea"
                        input_id="internal_comment"
                        v-model="form.internal_comment"
                        >Internal Comments</EthInput
                    >
                </div>

                <div class="edit_general">
                    <EthInput
                        input_type="textarea"
                        input_id="general_comment"
                        v-model="form.general_comment"
                        >General Comments</EthInput
                    >
                </div>
            </fieldset>

            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'codicology'" class="editcodexcontainer">
            <fieldset class="editcodex_materiality">
                <legend class="editcodex_sectionheading">Materiality</legend>
                <div class="edit_material">
                    <EthInput
                        input_type="single_choice"
                        input_id="material"
                        :choices="materials"
                        v-model="form.material_id"
                    >
                        Material
                    </EthInput>
                </div>

                <div class="edit_ink">
                    <EthInput
                        input_type="single_choice"
                        input_id="ink"
                        :choices="inks"
                        v-model="form.ink_id"
                    >
                        Ink
                    </EthInput>
                </div>

                <div class="edit_cover">
                    <EthInput
                        input_type="single_choice"
                        input_id="cover"
                        :choices="covers"
                        v-model="form.cover_id"
                    >
                        Cover
                    </EthInput>
                </div>

                <div class="edit_structure">
                    <EthInput
                        input_type="single_choice"
                        input_id="quire_structure"
                        :choices="quire_structures"
                        v-model="form.quire_structure_id"
                    >
                        Quire Structure
                    </EthInput>
                </div>

                <div v-if="form.quire_structure_id == 1" class="edit_numquire">
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

                <div v-if="form.quire_structure_id == 2" class="edit_numquire">
                    <EthInput
                        input_type="number"
                        input_id="quire_number"
                        v-model="form.quire_number"
                        >Number of Quires</EthInput
                    >
                    <div class="edit_quirenum">
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

                <div v-if="form.quire_structure_id == 3" class="edit_numquire">
                    <EthInput
                        input_type="number"
                        input_id="quire_number"
                        v-model="form.quire_number"
                        >Number of Quires</EthInput
                    >
                </div>
                <div v-if="form.quire_structure_id == 3" class="edit_quirenum">
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

                <div class="edit_quirecomm">
                    <EthInput
                        input_type="textarea"
                        input_id="quire_comment"
                        v-model="form.quire_comment"
                        >Comments on Quire(s)</EthInput
                    >
                </div>

                <div class="edit_bindingdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="binding_description"
                        v-model="form.binding_description"
                        >Binding Description</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_measurements">
                <legend class="editcodex_sectionheading">Measurements</legend>
                <div class="edit_dimknown">
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
                    <div class="edit_pagewidth">
                        <EthInput
                            input_type="text"
                            input_id="fragment_width"
                            v-model="form.fragment_width"
                            >Fragment Width (cm)</EthInput
                        >
                    </div>
                    <div class="edit_pageheight">
                        <EthInput
                            input_type="text"
                            input_id="fragment_height"
                            v-model="form.fragment_height"
                            >Fragment Height (cm)</EthInput
                        >
                    </div>
                </template>

                <template v-if="form.page_dimensions_known">
                    <div class="edit_pagewidth">
                        <EthInput
                            input_type="text"
                            input_id="full_page_width"
                            v-model="form.full_page_width"
                            >Full Page Width (cm)</EthInput
                        >
                    </div>
                    <div class="edit_pageheight">
                        <EthInput
                            input_type="text"
                            input_id="full_page_height"
                            v-model="form.full_page_height"
                            >Full Page Height (cm)</EthInput
                        >
                    </div>
                    <div class="edit_pratio">
                        <EthRatio
                            :dividend="form.full_page_width"
                            :divisor="form.full_page_height"
                            >Full Page Width/Height Ratio</EthRatio
                        >
                    </div>

                    <div class="edit_stable">
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
                        <div class="edit_uppmarg">
                            <EthInput
                                input_type="text"
                                input_id="upper_margin"
                                v-model="form.upper_margin"
                                >Upper Margin (cm)</EthInput
                            >
                        </div>
                        <div class="edit_lowmarg">
                            <EthInput
                                input_type="text"
                                input_id="lower_margin"
                                v-model="form.lower_margin"
                                >Lower Margin (cm)</EthInput
                            >
                        </div>
                        <div class="edit_ulratio">
                            <EthRatio
                                :dividend="form.upper_margin"
                                :divisor="form.lower_margin"
                                >Upper/Lower Margin Ratio</EthRatio
                            >
                        </div>

                        <div class="edit_innmarg">
                            <EthInput
                                input_type="text"
                                input_id="inner_margin"
                                v-model="form.inner_margin"
                                >Inner Margin (cm)</EthInput
                            >
                        </div>
                        <div class="edit_outmarg">
                            <EthInput
                                input_type="text"
                                input_id="outer_margin"
                                v-model="form.outer_margin"
                                >Outer Margin (cm)</EthInput
                            >
                        </div>
                        <div class="edit_ioratio">
                            <EthRatio
                                :dividend="form.inner_margin"
                                :divisor="form.outer_margin"
                                >Inner/Outer Margin Ratio</EthRatio
                            >
                        </div>

                        <div class="edit_blockwidth">
                            <EthInput
                                input_type="text"
                                input_id="full_text_block_width"
                                v-model="form.full_text_block_width"
                                >Full Text Block Width (cm)</EthInput
                            >
                        </div>
                        <div class="edit_blockheight">
                            <EthInput
                                input_type="text"
                                input_id="full_text_block_height"
                                v-model="form.full_text_block_height"
                                >Full Text Block Height (cm)</EthInput
                            >
                        </div>
                        <div class="edit_bratio">
                            <EthRatio
                                :dividend="form.full_text_block_width"
                                :divisor="form.full_text_block_height"
                                >Full Text Block Width/Height Ratio</EthRatio
                            >
                        </div>
                    </template>
                </template>

                <div class="edit_meascomm">
                    <EthInput
                        input_type="textarea"
                        input_id="measurement_comment"
                        v-model="form.measurement_comment"
                        >Comments on Measurements</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_textualfeatures">
                <legend class="editcodex_sectionheading">
                    Textual Features
                </legend>
                <div class="edit_scripts">
                    <EthInput
                        input_type="multi_choice_scripts"
                        input_id="scripts"
                        :choices="scripts_all"
                        v-model="form.scripts"
                    >
                        Scripts
                    </EthInput>
                </div>
                <div class="edit_numhands">
                    <EthInput
                        input_type="number"
                        input_id="hand_number"
                        v-model="form.hand_number"
                        >Number of Hands</EthInput
                    >
                </div>
                <div class="edit_scriptdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="script_description"
                        v-model="form.script_description"
                        >Description of Scripts</EthInput
                    >
                </div>
                <div class="edit_paratext">
                    <EthInput
                        input_type="multi_choice"
                        input_id="paratexts"
                        :choices="paratexts_all"
                        v-model="form.paratexts"
                    >
                        Paratexts
                    </EthInput>
                </div>

                <div class="edit_paradesc">
                    <EthInput
                        input_type="textarea"
                        input_id="paratext_description"
                        v-model="form.paratext_description"
                        >Description of Paratexts</EthInput
                    >
                </div>
                <div class="edit_punctuation">
                    <EthInput
                        input_type="multi_choice"
                        input_id="punctuations"
                        :choices="punctuations_all"
                        v-model="form.punctuations"
                    >
                        Punctuation
                    </EthInput>
                </div>
                <div class="edit_diacritics">
                    <EthInput
                        input_type="multi_choice"
                        input_id="diacritics"
                        :choices="diacritics_all"
                        v-model="form.diacritics"
                    >
                        Diacritics
                    </EthInput>
                </div>
                <div class="edit_diacritdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="diacritic_description"
                        v-model="form.diacritic_description"
                        >Description of Diacritics</EthInput
                    >
                </div>
                <div class="edit_critical">
                    <EthInput
                        input_type="multi_choice"
                        input_id="critical_symbols"
                        :choices="critical_symbols_all"
                        v-model="form.critical_symbols"
                    >
                        Critical Symbols
                    </EthInput>
                </div>

                <div class="edit_decorative">
                    <EthInput
                        input_type="multi_choice"
                        input_id="decorations"
                        :choices="decorations_all"
                        v-model="form.decorations"
                    >
                        Decorative Symbols
                    </EthInput>
                </div>

                <div class="edit_decodesc">
                    <EthInput
                        input_type="textarea"
                        input_id="decoration_description"
                        v-model="form.decoration_description"
                        >Description of Decorative Symbols</EthInput
                    >
                </div>

                <div class="edit_pagination">
                    <EthInput
                        input_type="single_choice"
                        input_id="pagination"
                        :choices="paginations"
                        v-model="form.pagination_id"
                    >
                        Pagination
                    </EthInput>
                </div>

                <div class="edit_quiresig">
                    <EthInput
                        input_type="single_choice"
                        input_id="quire_signature"
                        :choices="quire_signatures"
                        v-model="form.quire_signature_id"
                    >
                        Quire Signatures
                    </EthInput>
                </div>
            </fieldset>

            <button class="submitbutton" @click="submit">
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'conservation'" class="editcodexcontainer">
            <fieldset class="editcodex_conservanalys">
                <legend class="editcodex_sectionheading">
                    Conservation and Analysis
                </legend>
                <div class="edit_storage">
                    <EthInput
                        input_type="single_choice"
                        input_id="storage_condition"
                        :choices="storage_conditions"
                        v-model="form.storage_condition_id"
                    >
                        Storage Condition
                    </EthInput>
                </div>
                <div class="edit_conshist">
                    <EthInput
                        input_type="textarea"
                        input_id="conservation_history"
                        v-model="form.conservation_history"
                        >Conservation History</EthInput
                    >
                </div>
                <div class="edit_scientific">
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
            </fieldset>

            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'provenance'" class="editcodexcontainer">
            <fieldset class="editcodex_provenance">
                <legend class="editcodex_sectionheading">Provenance</legend>
                <div class="edit_sciex">
                    <label :for="scientifically_excavated"
                        >Scientifically Excavated</label
                    >
                    <input
                        :id="scientifically_excavated"
                        type="checkbox"
                        v-model="form.scientifically_excavated"
                    />
                </div>

                <div class="edit_excomm">
                    <EthInput
                        input_type="textarea"
                        input_id="excavation_comment"
                        v-model="form.excavation_comment"
                    >
                        Comments on Excavation
                    </EthInput>
                </div>

                <div class="edit_anciprov">
                    <EthInput
                        input_type="single_choice"
                        input_id="ancient_provenance"
                        :choices="ancient_provenances"
                        v-model="form.ancient_provenance_id"
                    >
                        Ancient Provenance
                    </EthInput>
                </div>

                <div class="edit_ancicert">
                    <EthInput
                        input_type="single_choice"
                        input_id="ancient_provenance_certainty"
                        :choices="ancient_provenance_certainties"
                        v-model="form.ancient_provenance_certainty_id"
                    >
                        Certainty of A. Provenance
                    </EthInput>
                </div>

                <div class="edit_ancicomm">
                    <EthInput
                        input_type="textarea"
                        input_id="ancient_provenance_comment"
                        v-model="form.ancient_provenance_comment"
                    >
                        Comments on Ancient Provenance
                    </EthInput>
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
                <div class="edit_legalclass">
                    <EthInput
                        input_type="legal_choice"
                        input_id="legal_classification"
                        :choices="legal_classifications"
                        v-model="form.legal_classification_id"
                    >
                        Legal Classification
                    </EthInput>
                </div>

                <div class="edit_legalcomm">
                    <EthInput
                        input_type="textarea"
                        input_id="legal_classification_explanation"
                        v-model="form.legal_classification_explanation"
                        >Comments on Legal Classification</EthInput
                    >
                </div>

                <div class="edit_transactions">
                    <EthInput
                        input_type="multi_choice"
                        input_id="purchases"
                        :choices="purchases_all"
                        v-model="form.purchases"
                    >
                        Transactions
                    </EthInput>
                </div>
            </fieldset>
            <button class="submitbutton" @click.prevent="submit">
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'images'" class="editcodexcontainer">
            <fieldset class="editcodex_images">
                <legend class="editcodex_sectionheading">Images</legend>
                <div class="edit_img">
                    <EthInput
                        input_type="textarea"
                        input_id="images_info"
                        v-model="form.images_info"
                        >Image Information</EthInput
                    >
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
                <div class="images_rows">
                    <div
                        class="box"
                        v-for="(image, index) in form.images"
                        :key="image.id"
                    >
                        <a :href="'/storage/' + image.filename" target="_blank">
                            <img
                                :src="'/storage/' + image.filename"
                                height="360"
                            />
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
        </div>
    </form>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref, onMounted } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import EthInput from "../Components/EthInput.vue";
import EthRatio from "../Components/EthRatio.vue";

const props = defineProps({
    analyses: Array,
    analyses_all: Array,
    ancient_provenance: Object,
    ancient_provenances: Array,
    ancient_provenance_certainty: Object,
    ancient_provenance_certainties: Array,
    cover: Object,
    covers: Array,
    critical_symbols: Array,
    critical_symbols_all: Array,
    dating_certainty: Object,
    dating_certainties: Array,
    dating_methods: Array,
    dating_methods_all: Array,
    decorations: Array,
    decorations_all: Array,
    diacritics: Array,
    diacritics_all: Array,
    document: Object,
    genres: Array,
    genres_all: Array,
    images: Array,
    ink: Object,
    inks: Array,
    languages: Array,
    languages_all: Array,
    legal_classification: Object,
    legal_classifications: Array,
    licenses: Array,
    material: Object,
    materials: Array,
    modern_collections: Array,
    modern_collections_all: Array,
    newimages: Array,
    pagination: Object,
    paginations: Array,
    paratexts: Array,
    paratexts_all: Array,
    published: Boolean,
    punctuations: Array,
    punctuations_all: Array,
    purchases: Array,
    purchases_all: Array,
    quire_signature: Array,
    quire_signatures: Array,
    quire_structure: Object,
    quire_structures: Array,
    scripts: Array,
    scripts_all: Array,
    storage_condition: Object,
    storage_conditions: Array,
    tags: Array,
    tags_all: Array,
});

const form = useForm({
    id: props.document.id,
    published: props.document.published == 1 ? true : false,
    standard_name: props.document.standard_name,
    other_names: props.document.other_names,
    publication: props.document.publication,
    current_shelfmarks: props.document.current_shelfmarks,
    trismegistos_id: props.document.trismegistos_id,
    title: props.document.title,
    genres: props.genres,
    scientifically_excavated:
        props.document.scientifically_excavated == 1 ? true : false,
    excavation_comment: props.document.excavation_comment,
    ancient_author: props.document.ancient_author,
    tags: props.tags,
    content_description: props.document.content_description,
    start_year: props.document.start_year,
    end_year: props.document.end_year,
    languages: props.languages,
    dating_methods: props.dating_methods,
    dating_certainty_id: props.document.dating_certainty_id,
    dating_comment: props.document.dating_comment,
    bibliography: props.document.bibliography,
    internal_comment: props.document.internal_comment,
    general_comment: props.document.general_comment,
    material_id: props.document.material_id,
    page_dimensions_known:
        props.document.page_dimensions_known == 1 ? true : false,
    fragment_width: props.document.fragment_width,
    fragment_height: props.document.fragment_height,
    full_page_width: props.document.full_page_width,
    full_page_height: props.document.full_page_height,
    textbox_size_stable: props.document.textbox_size_stable == 1 ? true : false,
    upper_margin: props.document.upper_margin,
    lower_margin: props.document.lower_margin,
    inner_margin: props.document.inner_margin,
    outer_margin: props.document.outer_margin,
    full_text_block_width: props.document.full_text_block_width,
    full_text_block_height: props.document.full_text_block_height,
    measurement_comment: props.document.measurement_comment,
    scripts: props.scripts,
    hand_number: props.document.hand_number,
    script_description: props.document.script_description,
    paratexts: props.paratexts,
    paratext_description: props.document.paratext_description,
    punctuations: props.punctuations,
    diacritics: props.diacritics,
    diacritic_description: props.document.diacritic_description,
    critical_symbols: props.critical_symbols,
    decorations: props.decorations,
    decoration_description: props.document.decoration_description,
    pagination_id: props.document.pagination_id,
    cover_id: props.document.cover_id,
    ink_id: props.document.ink_id,
    quire_signature_id: props.document.quire_signature_id,
    quire_structure_id: props.document.quire_structure_id,
    quire_number: props.document.quire_number,
    bifolia: props.document.bifolia ? JSON.parse(props.document.bifolia) : [],
    quire_comment: props.document.quire_comment,
    binding_description: props.document.binding_description,
    storage_condition_id: props.document.storage_condition_id,
    conservation_history: props.document.conservation_history,
    analyses: props.analyses,
    analyses_comment: props.document.analyses_comment,
    ancient_provenance_id: props.document.ancient_provenance_id,
    ancient_provenance_certainty_id:
        props.document.ancient_provenance_certainty_id,
    ancient_provenance_comment: props.document.ancient_provenance_comment,
    modern_collections: props.modern_collections,
    legal_classification_id: props.document.legal_classification_id,
    legal_classification_explanation:
        props.document.legal_classification_explanation,
    purchases: props.purchases,
    images_info: props.document.images_info,
    images: props.images,
});

let removeStartEventListener = Inertia.on("before", (event) => {
    if (form.isDirty && !submitted) {
        if (
            confirm(
                "You have unsaved data in your form. Are you sure you want to leave the page?"
            )
        ) {
            removeStartEventListener();
            window.onbeforeunload = null;
        } else if (submitted) {
            removeStartEventListener();
            window.onbeforeunload = null;
        } else {
            return false;
        }
    }
});

window.onbeforeunload = (s) => (form.isDirty ? "" : null);

onMounted(() => {
    if (!form.bifolia) {
        form.bifolia[0] = 0;
    }
});

const loadImages = ref(null);

let activetab = ref("general");
let submitted = false;

function range(start, end) {
    var foo = [];
    for (var i = start; i <= end; i++) {
        foo.push(i);
    }
    return foo;
}

function delimage(image) {
    let image_id = image.id;
    if (confirm("Do you want to delete this image?")) {
        Inertia.post("/delimage/" + image_id, null, {
            preserveState: true,
            preserveScroll: true,
            resetOnSuccess: false,
            onSuccess: () => {
                let index = form.images.findIndex(function (o) {
                    return o.id === image_id;
                });
                if (index !== -1) {
                    form.images.splice(index, 1);
                }
            },
        });
    }
}

function addimages(imagefiles) {
    Inertia.post(
        "/addimages",
        { images: imagefiles, document_id: props.document.id },
        {
            preserveState: true,
            preserveScroll: true,
            resetOnSuccess: false,
            onSuccess: () => {
                props.images.forEach((image, index) => {
                    if (!form.images.findIndex((x) => x.id == image.id)) {
                        form.images.push(image);
                    }
                });
                if (form.images.length == 0) {
                    props.images.forEach((image, index) => {
                        form.images.push(image);
                    });
                }
            },
        }
    );
    this.loadImages.value = null;
}

function submit() {
    submitted = true;
    form.post("/codex_update/" + props.document.id);
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
    width: 250px;
    height: 55px;
    padding: 10px;
    border-radius: 15px;
    justify-self: center;
    align-self: center;
    margin: 20px;
}

.editcodexcontainer {
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
.edit_cover {
    grid-area: cover;
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
.edit_paratext {
    grid-area: paratext;
}
.edit_paradesc {
    grid-area: paradesc;
}
.edit_punctuation {
    grid-area: punctuation;
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
.edit_decorative {
    grid-area: decorative;
}
.edit_decodesc {
    grid-area: decodesc;
}
.edit_quiresig {
    grid-area: quiresig;
}

.editcodex_textualfeatures {
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
    margin-top: 15px;
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
    margin-top: 15px;
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
</style>
