<template>
    <div class="codexmenu">
        <button
            type="button"
            :class="activetab == 'general' ? 'selected' : 'unselected'"
            @click="activetab = 'general'"
        >
            General Information
        </button>

        <button
            type="button"
            :class="activetab == 'codicology' ? 'selected' : 'unselected'"
            @click="activetab = 'codicology'"
        >
            Codicology
        </button>

        <button
            type="button"
            :class="activetab == 'conservation' ? 'selected' : 'unselected'"
            @click="activetab = 'conservation'"
        >
            Conservation and Analysis
        </button>

        <button
            type="button"
            :class="activetab == 'provenance' ? 'selected' : 'unselected'"
            @click="activetab = 'provenance'"
        >
            Provenance
        </button>

        <button
            type="button"
            :class="activetab == 'images' ? 'selected' : 'unselected'"
            @click="activetab = 'images'"
        >
            Images
        </button>
    </div>

    <div class="mainstyle">
        <button
            class="topbutton"
            v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
            @click="edit_codex(document.id)"
        >
            Edit Codex
        </button>

        <div class="topbutton">({{ props.current }} of {{ props.total }})</div>

        <button
            :disabled="next === -1"
            class="topbutton"
            @click="show_codex(props.next)"
        >
            Next
        </button>

        <button
            :disabled="prev === -1"
            class="topbutton"
            @click="show_codex(props.prev)"
        >
            Previous
        </button>

        <div class="nameheading">
            {{ document.standard_name }}
        </div>

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
                    <br>
                    <label>Completed</label>
                    <div class="showcodex_bool">
                        {{ document.completed ? "Yes" : "No" }}
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
                        <a
                            :href="
                                'https://www.trismegistos.org/text/' +
                                document.trismegistos_id
                            "
                            target="_blank"
                        >
                            {{ document.trismegistos_id }}
                        </a>
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

                <div class="show_works">
                    <label>Works</label>
                    <div class="choicelist-table">
                        <table>
                            <tr>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Passage</th>
                                <th>Comment</th>
                            </tr>

                            <tr
                                v-for="work in works.sort((a, b) =>
                                    a.author.name.localeCompare(b.author.name)
                                )"
                                :key="work.id"
                            >
                                <td>
                                    <label :title="work.author.altnames">
                                        {{ work.author.name }}</label
                                    >
                                </td>
                                <td>
                                    <label :title="work.altnames">{{
                                        work.name
                                    }}</label>
                                </td>
                                <td>
                                    <label>{{
                                        work.pivot.passages
                                            ? work.pivot.passages
                                            : ""
                                    }}</label>
                                </td>
                                <td>
                                    <label>{{
                                        work.pivot.passage_comment
                                            ? work.pivot.passage_comment
                                            : ""
                                    }}</label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="show_genre">
                    <label>Genres</label>
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

                <div class="show_gregory">
                    <label>Gregory's Rule</label>
                    <div class="showcodex_string_short">
                        {{
                            gregorys_rule.length > 0
                                ? gregorys_rule[0].name
                                : ""
                        }}
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

                <div class="show_gregorycomm">
                    <label>Comments on Gregory's Rule</label>
                    <div
                        class="showcodex_text"
                        v-html="document.gregorys_rule_comment"
                    />
                </div>
            </fieldset>

            <fieldset class="showcodex_measurements">
                <legend class="sectionheading">Measurements</legend>
                <template v-if="!document.page_dimensions_known">
                    <div class="show_pagewidth">
                        <label>Fragment Width (cm)</label>
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
                </template>

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

                <div class="show_columns">
                    <label>Number of Columns</label>
                    <div class="showcodex_string_short">
                        {{ document.columns }}
                    </div>
                </div>

                <div class="show_columnlines">
                    <label>Lines per Column</label>
                    <div class="showcodex_string_short">
                        {{ document.columnlines }}
                    </div>
                </div>

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
                    <template v-if="checklanguage(1)">
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
                    </template>

                    <template v-if="checklanguage(2)">
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
                    </template>
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

                <div class="show_micrograph">
                    <template v-if="checkmicrograph(images)">
                        <label>Micrographs</label>
                        <div class="micrograph_gallery">
                            <template
                                v-for="(image, index) in images"
                                :key="image.id"
                            >
                                <div
                                    v-if="image.micrograph"
                                    class="micrograph_box"
                                >
                                    <img
                                        class="micrograph_image"
                                        :src="'/storage/' + image.filename"
                                    />

                                    <div
                                        class="micrograph_description"
                                        v-html="image.description"
                                    />
                                </div>
                            </template>
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
                        >Excavated by Permit</label
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
                        {{ ancient_provenance_full }}
                    </div>
                </div>


                <div class="show_ancicomm">
                    <label>Comments on Ancient Provenance</label>
                    <div
                        class="showcodex_text"
                        v-html="document.ancient_provenance_comment"
                    />
                </div>

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

                <div class="show_collections">
                    <label>Current Collection(s)</label>
                    <div
                        v-for="collection in collections"
                        :key="collection.id"
                        class="showcodex_multi_stack"
                    >
                        {{ collection.name }}
                    </div>
                </div>


                <div class="show_transactions">
                    <label>Transactions</label>
                    <div
                        v-for="transaction in transactions"
                        :key="transaction.id"
                        class="showcodex_multi_stack"
                    >
                        <button
                            @click="
                                seltrans =
                                    JSON.stringify(seltrans) ===
                                    JSON.stringify(transaction)
                                        ? {}
                                        : transaction
                            "
                        >
                            Info
                        </button>
                        {{ transaction.name }} ({{
                            transaction.year +
                            (transaction.month ? "-" + transaction.month : "") +
                            (transaction.day ? "-" + transaction.day : "")
                        }})
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
                                        padding: 2px 3px;
                                        font-size: 12px;
                                    "
                                    >Show
                                </Link>
                                {{ codex.standard_name }}
                            </p>
                            <h2>Parties to the Transaction</h2>
                            <p
                                v-for="party in seltrans.transaction_parties"
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
                        <div
                            class="showcodex_imagebox"
                            v-if="!image.micrograph"
                        >
                            <a
                                :href="'/storage/' + image.filename"
                                target="_blank"
                            >
                                <div class="codex_image_container">
                                    <img :src="'/storage/' + image.filename" />
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
                            <div
                                class="showcodex_imgtext"
                                v-html="image.source"
                            />

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
import { router } from "@inertiajs/vue3";
import { reactive, ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
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
    gregorys_rule: Array,
    images: Array,
    inks: Array,
    languages: Array,
    legal_classification: Array,
    material: Array,
    collections: Array,
    newimages: Array,
    pagination: Array,
    paratexts: Array,
    punctuations: Array,
    transactions: Array,
    quire_signature: Array,
    quire_structure: Array,
    scripts: Array,
    storage_condition: Array,
    tags: Array,
    works: Array,

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
    languages_search: Array,
    languages_incl: Boolean,
    genres_search: Array,
    genres_incl: Boolean,
    tags_search: Array,
    tags_incl: Boolean,
    /* Dating */
    earliest_date: Number,
    latest_date: Number,
    exclusive_date: Boolean,
    dating_methods_search: Array,
    dating_methods_incl: Boolean,
    dating_certainties_search: Array,
    /* Materiality */
    materials_search: Array,
    gregorys_rules_search: Array,
    inks_search: Array,
    inks_incl: Boolean,
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
    full_page_ratio_min: Number,
    full_page_ratio_max: Number,
    full_text_block_ratio_min: Number,
    full_text_block_ratio_max: Number,
    uplow_margins_ratio_min: Number,
    uplow_margins_ratio_max: Number,
    inout_margins_ratio_min: Number,
    inout_margins_ratio_max: Number,
    columns_min: Number,
    columns_max: Number,
    columnlines_min: Number,
    columnlines_max: Number,

    /* Palaeography */
    hand_number_min: Number,
    hand_number_max: Number,
    scripts_search: Array,
    scripts_incl: Boolean,
    diacritics_search: Array,
    diacritics_incl: Boolean,
    punctuations_search: Array,
    punctuations_incl: Boolean,
    paratexts_search: Array,
    paratexts_incl: Boolean,
    decorations_search: Array,
    decorations_incl: Boolean,
    critical_symbols_search: Array,
    critical_symbols_incl: Boolean,
    paginations_search: Array,
    quire_signatures_search: Array,
    /* Conservation and Analysis */
    storage_conditions_search: Array,
    analyses_search: Array,
    analyses_incl: Boolean,
    /* Provenance */
    scientifically_excavated: Number,
    ancient_provenances_search: Array,
    ancient_provenance_certainties_search: Array,
    transactions_search: Array,
    transactions_incl: Boolean,
    transaction_parties_search: Array,
    transaction_parties_incl: Boolean,
    legal_classifications_search: Array,

    sortfield: String,
    reverse: Number,

    tab: String,
    prev: Number,
    next: Number,
    current: Number,
    total: Number,

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
    s_standard_name: props.standard_name,
    s_publication: props.publication,
    s_current_shelfmarks: props.current_shelfmarks,
    s_trismegistos_id: props.trismegistos_id,
    /* Content */
    s_title: props.title,
    s_ancient_author: props.ancient_author,
    s_languages: props.languages_search ? props.languages_search : [],
    s_languages_incl: props.languages_incl,
    s_genres: props.genres_search ? props.genres_search : [],
    s_genres_incl: props.genres_incl,
    s_tags: props.tags_search ? props.tags_search : [],
    s_tags_incl: props.tags_incl,
    /* Dating */
    s_earliest_date: props.earliest_date,
    s_latest_date: props.latest_date,
    s_exclusive_date: props.exclusive_date,
    s_dating_methods: props.dating_methods_search
        ? props.dating_methods_search
        : [],
    s_dating_methods_incl: props.dating_methods_incl,
    s_dating_certainties: props.dating_certainties_search
        ? props.dating_certainties_search
        : [],
    /* Materiality */
    s_materials: props.materials_search ? props.materials_search : [],
    s_gregorys_rules: props.gregorys_rules_search
        ? props.gregorys_rules_search
        : [],
    s_inks: props.inks_search ? props.inks_search : [],
    s_inks_incl: props.inks_incl,
    s_covers: props.covers_search ? props.covers_search : [],
    s_quire_structures: props.quire_structures_search
        ? props.quire_structures_search
        : [],
    s_quirenum_min: props.quirenum_min,
    s_quirenum_max: props.quirenum_max,
    s_bifolianum_min: props.bifolianum_min,
    s_bifolianum_max: props.bifolianum_max,
    /* Measurement */
    s_full_page_width_min: props.full_page_width_min,
    s_full_page_width_max: props.full_page_width_max,
    s_full_page_height_min: props.full_page_height_min,
    s_full_page_height_max: props.full_page_height_max,
    s_full_text_block_width_min: props.full_text_block_width_min,
    s_full_text_block_width_max: props.full_text_block_width_max,
    s_full_text_block_height_min: props.full_text_block_height_min,
    s_full_text_block_height_max: props.full_text_block_height_max,
    s_upper_margin_min: props.upper_margin_min,
    s_upper_margin_max: props.upper_margin_max,
    s_lower_margin_min: props.lower_margin_min,
    s_lower_margin_max: props.lower_margin_max,
    s_inner_margin_min: props.inner_margin_min,
    s_inner_margin_max: props.inner_margin_max,
    s_outer_margin_min: props.outer_margin_min,
    s_outer_margin_max: props.outer_margin_max,
    s_full_page_ratio_min: props.full_page_ratio_min,
    s_full_page_ratio_max: props.full_page_ratio_max,
    s_full_text_block_ratio_min: props.full_text_block_ratio_min,
    s_full_text_block_ratio_max: props.full_text_block_ratio_max,
    s_uplow_margins_ratio_min: props.uplow_margins_ratio_min,
    s_uplow_margins_ratio_max: props.uplow_margins_ratio_max,
    s_inout_margins_ratio_min: props.inout_margins_ratio_min,
    s_inout_margins_ratio_max: props.inout_margins_ratio_max,
    s_columns_min: props.columns_min,
    s_columns_max: props.columns_max,
    s_columnlines_min: props.columnlines_min,
    s_columnlines_max: props.columnlines_max,

    /* Palaeography */
    s_hand_number_min: props.hand_number_min,
    s_hand_number_max: props.hand_number_max,
    s_scripts: props.scripts_search ? props.scripts_search : [],
    s_scripts_incl: props.scripts_incl,
    s_diacritics: props.diacritics_search ? props.diacritics_search : [],
    s_diacritics_incl: props.diacritics_incl,
    s_punctuations: props.punctuations_search ? props.punctuations_search : [],
    s_punctuations_incl: props.punctuations_incl,
    s_paratexts: props.paratexts_search ? props.paratexts_search : [],
    s_paratexts_incl: props.paratexts_incl,
    s_decorations: props.decorations_search ? props.decorations_search : [],
    s_decorations_incl: props.decorations_incl,
    s_critical_symbols: props.critical_symbols_search
        ? props.critical_symbols_search
        : [],
    s_critical_symbols_incl: props.critical_symbols_incl,
    s_paginations: props.paginations_search ? props.paginations_search : [],
    s_quire_signatures: props.quire_structures_search
        ? props.quire_structures_search
        : [],
    /* Conservation and Analysis */
    s_storage_conditions: props.storage_conditions_search
        ? props.storage_conditions_search
        : [],
    s_analyses: props.analyses_search ? props.analyses_search : [],
    s_analyses_incl: props.analyses_incl,
    /* Provenance */
    s_scientifically_excavated: props.scientifically_excavated,
    s_ancient_provenances: props.ancient_provenances_search
        ? props.ancient_provenances_search
        : [],
    s_ancient_provenance_certainties:
        props.ancient_provenance_certainties_search
            ? props.ancient_provenance_certainties_search
            : [],
    s_transactions: props.transactions_search ? props.transactions_search : [],
    s_transactions_incl: props.transaction_parties_incl,
    s_transaction_parties: props.transaction_parties_search
        ? props.transaction_parties_search
        : [],
    s_transaction_parties_incl: props.transaction_parties_incl,
    s_legal_classifications: props.legal_classifications_search
        ? props.legal_classifications_search
        : [],
    sortfield: props.sortfield ? props.sortfield : 4,
    reverse: props.reverse,
    tab: props.tab ? props.tab : "general",
});

const bifolia = props.document.bifolia
    ? JSON.parse(props.document.bifolia)
    : [];
if (!bifolia) {
    bifolia[0] = 0;
}

let ancient_provenance_full = "";
const prov = props.ancient_provenance;
if (prov ? prov.length > 0 : false) {
    ancient_provenance_full = ancient_provenance_full.concat(prov[0].name);
    let parent = prov[0].parent_recursive;
    while (parent) {
        ancient_provenance_full = ancient_provenance_full.concat(
            ", ",
            parent.name
        );
        parent = parent.parent_recursive;
    }
}

const loadImages = ref(null);

let activetab = ref(props.tab);

let seltrans = ref({});

function checkmicrograph(images) {
    let found = false;
    for (const image of images) {
        if (image.micrograph) {
            found = true;
        }
    }
    return found;
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

function show_codex(id) {
    seltrans = ref({});
    form.tab = activetab.value;
    form.post("/codex_show/" + id, {
        queryStringArrayFormat: "indices",
        replace: true,
    });
}

function edit_codex(id) {
    form.tab = activetab.value;
    form.post("/codex_edit/" + id, {
        queryStringArrayFormat: "indices",
        replace: true,
    });
}
</script>

<style></style>
