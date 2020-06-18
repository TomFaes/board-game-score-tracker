<template>
    <div>
        <h1>Roadmap</h1>

         <!--
        <release-item>
            <template slot="title"></template>
            <template slot="features">

            </template>
            <template slot="stats">

            </template>
            <template slot="bugs">

            </template>
        </release-item>
        -->

        <release-item>
            <template slot="title">12/06/2020: new Release</template> git
            <template slot="features">
                <ul>
                    <li>Update package to the latest version</li>
                    <li>Create roadmap</li>
                    <li>Choose favorite group</li>
                    <li>build in that the real login links are used on the form and the test links with local host</li>
                    <li>add Cookie consent</li>
                </ul>
            </template>
            <template slot="bugs">
                 <ul>
                    <li>after login there still was a search for groups</li>
                    <li>deleting a game from group games doesn't work(000webhost):  net::ERR_HTTP2_PROTOCOL_ERROR</li>
                    <li>When there was a tie for victory, only 1 person got a victory in the stats page</li>
                </ul>
            </template>
        </release-item>

         <release-item>
            <template slot="title">24/04/2020: Release beta version</template>
        </release-item>

        <release-item>
            <template slot="title">Undefined release</template>
            <template slot="features">
                <ul>
                    <li>Add option to delete groups/played games but also to recover them</li>
                    <li>add contactform for bugs, features(add option to make the list public(create a package so it can be used in other programs</li>
                    <li>rework the user page(it has not been set to the new vue)</li>
                    <li>rework the group page(it has not been set to the new vue)</li>
                    <li>make table sortable with stats</li>
                </ul>

            </template>
            <template slot="stats">
                <ul>
                    <li>Group: per game high scores/low scores/average</li>
                    <li>Player: gamestats</li>
                    <li>Game: high scores/low scores/average</li>
                    <li></li>
                    <li></li>
                </ul>
            </template>
            <template slot="bugs">
                 <ul>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </template>
        </release-item>
    </div>
</template>

<script>
import releaseItem from '../../components/tools/releaseItem.vue';

    export default {
        components: {
            releaseItem,
        },

        data (){
            return {

            }
        },

        props: {

         },

        methods: {

        },

        mounted () {

        }
    }

</script>
<style scoped>



</style>
