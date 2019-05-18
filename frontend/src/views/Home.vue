<template>
  <v-container>
    <v-layout row wrap>
      <v-flex xs12>
        <v-subheader>
          Tweet
        </v-subheader>
      </v-flex>
      <v-flex mb-2>
        <v-sheet class="pa-4" elevation=6>
          <v-form @submit="postTweet" onSubmit="return false;">
            <v-text-field 
                    v-model="tweet"
                    label="tweet"
                    required
            ></v-text-field>
            <v-btn type="submit" color="primary">ツイート</v-btn>
          </v-form>
        </v-sheet>
      </v-flex>

      <v-flex xs12>
        <v-sheet class="pa-4" elevation=6>
          <Timeline v-bind:timelines="timelines"/>
        </v-sheet>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import { CREATE_TWEET } from "../graphql/mutation";
  import { TIMELINE } from "../graphql/query";
  import Timeline from "../components/Timeline.vue"

  export default {
    data: () => ({
      tweet: '',
      timelines: []
    }),
    methods: {
      postTweet(e) {
        this.$apollo.mutate({ 
          mutation: CREATE_TWEET,
          variables: {
            tweet: this.tweet, 
          },
        }).then((data) => {
          this.$apollo.queries.timelines.refetch({
            id: 0
          })
        });
      }
    },
    apollo: {
      // こことdataのtimelinesはリンクしている
      timelines: {
        query: TIMELINE,
        loadingKey: 'loading',
        variables () {
          return {
            id: 0
          }
        },
        update (data) {
          return data.Timeline;
        }
      }
    }
  }
</script>
