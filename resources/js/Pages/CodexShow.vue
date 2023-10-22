<template>
    <div class="codexmenu">
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

    <div class="mainstyle">
        <Link
            class="topbutton"
            as="button"
            type="button"
            v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
            :href="'/codex_edit/' + document.id"
        >
            Edit Codex
        </Link>
        <div v-if="activetab == 'general'" class="maincontainer">
            <fieldset class="showcodex_generalgrid">
                <legend class="sectionheading">Publication Info</legend>
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
                <legend class="sectionheading">Content</legend>
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
                <legend class="sectionheading">Date</legend>
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
                <legend class="sectionheading">Comments</legend>
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

        <div v-if="activetab == 'codicology'" class="maincontainer">
            <fieldset class="showcodex_materiality">
                <legend class="sectionheading">Materiality</legend>
                <div class="show_material">
                    <label>Material</label>
                    <div class="showcodex_string_short">
                        {{ material.length > 0 ? material[0].name : "" }}
                    </div>
                </div>

                <div class="show_ink">
                    <label>Inks</label>
                    <div class="showcodex_multi">
                        <div
                            v-for="ink in inks"
                            :key="ink.id"
                            class="showcodex_multi_line"
                        >
                            {{ ink.name }}
                        </div>
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

                <div class="show_bindingdesc">
                    <label>Description of Binding</label>
                    <div
                        class="showcodex_text"
                        v-html="document.binding_description"
                    />
                </div>

                <div class="show_inksdesc">
                    <label>Description of Inks</label>
                    <div
                        class="showcodex_text"
                        v-html="document.inks_description"
                    />
                </div>

                <div class="show_coverdesc">
                    <label>Description of Cover</label>
                    <div
                        class="showcodex_text"
                        v-html="document.cover_description"
                    />
                </div>

                <div class="show_quirecomm">
                    <label>Comments on Quire(s)</label>
                    <div
                        class="showcodex_text"
                        v-html="document.quire_comment"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_measurements">
                <legend class="sectionheading">Measurements</legend>
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

                <div class="show_textblockcomm">
                    <label>Comments on Full Text Block</label>
                    <div
                        class="showcodex_text"
                        v-html="document.full_text_block_comment"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_textualfeatures">
                <legend class="sectionheading">Textual Features</legend>
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
                <div class="show_punctuationdesc">
                    <label>Description of Punctuation</label>
                    <div
                        class="showcodex_text"
                        v-html="document.punctuation_description"
                    />
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
                <div class="show_criticaldesc">
                    <label>Description of Critical Symbols</label>
                    <div
                        class="showcodex_text"
                        v-html="document.critical_symbols_description"
                    />
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
                <div class="show_paginationdesc">
                    <label>Description of Pagination</label>
                    <div
                        class="showcodex_text"
                        v-html="document.pagination_description"
                    />
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
                <div class="show_quiresigdesc">
                    <label>Description of Quire Signatures</label>
                    <div
                        class="showcodex_text"
                        v-html="document.quire_signatures_description"
                    />
                </div>
            </fieldset>
        </div>

        <div v-if="activetab == 'conservation'" class="maincontainer">
            <fieldset class="showcodex_conservanalys">
                <legend class="sectionheading">
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
                
                <div class="show_micrograph" v-if="checkmicrograph(images)">
                    <div>
                    <label>Micrographs</label>
                    </div>
                    <template v-for="(image, index) in images" :key="image.id">
                        <div class="micrograph_gallery">
                        <div class="micrograph_box" v-if="image.micrograph">

                            <img
                                :src="'/storage/' + image.filename"
                                height="120"
                                max-width="160"
                            />
                            <div
                                class="showcodex_microdesc"
                                v-html="image.description"
                            />
                        </div>
                        </div>
                    </template>
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

        <div v-if="activetab == 'provenance'" class="maincontainer">
            <fieldset class="showcodex_provenance">
                <legend class="sectionheading">Provenance</legend>
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
                            <h2>Date:</h2>
                            <p>
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
                                <h2>Description:</h2>
                                <div v-html="seltrans.description"></div>
                            </span>

                            <span v-if="seltrans.bibliography">
                                <h2>Bibliography:</h2>
                                <div v-html="seltrans.bibliography"></div>
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
                                {{ party.name }} ({{ party.pivot.party_role }})
                            </p>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div v-if="activetab == 'images'" class="maincontainer">
            <fieldset class="showcodex_images">
                <legend class="sectionheading">Images</legend>
                <div class="show_img">
                    <label>Image Information</label>
                    <div class="showcodex_text" v-html="document.images_info" />
                </div>

                <div class="codex_images_rows">
                    <template v-for="(image, index) in images" :key="image.id">
                        <div class="showcodex_imagebox" v-if="!image.micrograph">
                            <a :href="'/storage/' + image.filename" target="_blank">
                                <div class="codex_image_container">
                                <img
                                    :src="'/storage/' + image.filename"
                                />
                                </div>
                            </a>
                            <label
                                class="showcodex_labelpadding"
                                :for="'image_description_' + index"
                                >Description:
                            </label>
                            <div
                                class="showcodex_imgtext"
                                v-html="image.description"
                            />
                            <label>Source</label>
                            <div class="showcodex_imgtext" v-html="image.source" />

                            <label>License</label>
                            <div class="showcodex_string">
                                {{ image.license ? image.license.name : "" }}
                            </div>
                        </div>
                    </template>
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
    inks: Array,
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

function checkmicrograph(images) {
    let found = false
    for (const image of images) {
        if (image.micrograph) {
            found = true
        }
    }
    return found
}

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

<style></style>
