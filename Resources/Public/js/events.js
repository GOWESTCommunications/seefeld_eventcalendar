
document.addEventListener("DOMContentLoaded", function () {

    const template =
        `<div id="myEvents" class="imgGroup width">
            <template v-for="city in 5">
            <h2> {{ Cities[city-1] }} </h2>
            <div class="container">
            <div class="container events" data-flickity='{ "groupCells": true, "wrapAround": true, "prevNextButtons": true }'>
                <div v-for="event in events[city-1]" v-bind:key="event.id" class="col-12 col-sm-6 col-md-4 overviewItem">
                <article v-on:click="redirectToEvent(event.homepage)">
                    <div class="image" >
                        <picture>
                            <img :src="event.image" alt="event.title"></img>
                        </picture>
                    </div>
                    <div class="text">
                        <div class="inner">
                            <header>
                                <h4>{{ event.name }}</h4>
                            </header>
                            <p>Hi</p>
                            <p class="description" v-if="event.fromDate != event.toDate">Zwischen {{ event.fromDate }} und {{ event.toDate }}</p>
                            <p class="description" v-else> {{ event.toDate }}</p>
                            <p class="charge" v-if="!event.freeOfCharge">*kostenpflichtig</p>
                        </div>
                    </div>
                </article>
                </div>
                </div>
                </div>
            </template>
            </div>
        </div>`;

    const compiledTemplate = Vue.compile(template);

    new Vue({
        el: "#myEvents",
        // define data array
        data: {
            //Indexe stehen für Städte, Anordnung gleich wie im Cities Array
            events: {
                0: [],
                1: [],
                2: [],
                3: [],
                4: [],
            },
            Cities: ['Seefeld', 'Leutasch', 'Reith', 'Scharnitz', 'Mösern'],
        },
        created() {
            this.getEvents();
        },
        beforeMount() {
            this.addCssToHead('/typo3conf/ext/events_seefeld/Resources/Public/css/flickity.min.css');
            this.addCssToHead('/typo3conf/ext/events_seefeld/Resources/Public/css/events.css');
            // // this.addJsToBody('/typo3conf/ext/events_seefeld/Resources/Public/js/events.js');
            // this.addJsToBody('/typo3conf/ext/events_seefeld/Resources/Public/js/vue.min.js');
            // this.addJsToBody('/typo3conf/ext/events_seefeld/Resources/Public/js/flickity.min.js');

        },
        methods: {
            redirectToEvent(eventLink) {
                if (eventLink) {
                    window.open(eventLink, "_blank");
                }
                //if there's no website linked, link to event homepage
                else {
                    window.open('https://www.seefeld.com/veranstaltungskalender', "_blank");
                }

            },
            addCssToHead(linkToResource) {
                var link = document.createElement('link');
                link.href = linkToResource;
                link.rel  = 'stylesheet';
                $("head")[0].appendChild(link);
            },
            addJsToBody(linkToResource) {
                var script = document.createElement('script');
                script.type = "text/javascript";
                script.src = linkToResource;
                $("body")[0].appendChild(script);
            },
            getEvents() {
                var filePath = '../../../../../../../typo3temp/events/events_seefeld.json';
                
                //get event json file
                var json = null;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET", filePath, false);
                xmlhttp.send();
                if (xmlhttp.status == 200) {
                    json = xmlhttp.responseText;
                }

                json = JSON.parse(json);

                var buffer = this;

                json.forEach(event => {
                    event = JSON.parse(event);

                    //format Dates
                    event.fromDate = event.fromDate.substring(8) + "." + event.fromDate.substring(5, 7) + "." + event.fromDate.substring(0, 4);
                    event.toDate = event.toDate.substring(8) + "." + event.toDate.substring(5, 7) + "." + event.toDate.substring(0, 4);

                    switch (event.city) {
                        case 'Leutasch':
                            if (buffer.events[1].length < 20) {
                                buffer.events[1].push(event);
                            }
                            break;
                        case 'Seefeld':
                            if (buffer.events[0].length < 20) {
                                buffer.events[0].push(event);
                            }
                            break;
                        case 'Reith':
                            if (buffer.events[2].length < 20) {
                                buffer.events[2].push(event);
                            }
                            break;
                        case 'Mösern/Buchen':
                            if (buffer.events[4].length < 20) {
                                buffer.events[4].push(event);
                            }
                            break;
                        case 'Scharnitz':
                            if (buffer.events[3].length < 20) {
                                buffer.events[3].push(event);
                            }
                            break;
                        default:
                            if (buffer.events[0].length < 20) {
                                buffer.events[0].push(event);
                            }
                            break;
                    };
                })

            },
        },
        render(createElement) {
            return compiledTemplate.render.call(this, createElement)
        }
    });
});


