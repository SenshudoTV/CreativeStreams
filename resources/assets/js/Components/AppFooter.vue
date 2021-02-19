<template>
    <footer class="app--footer text-light mt-auto">
        <b-container>
            <b-row>
                <b-col :sm="12" :md="4">
                    <h4>Company</h4>
                    <ul class="app--footer__links">
                        <li>
                            <a
                                href="https://www.senshudo.tv/about"
                                rel="noopener noreferrer"
                                target="_blank"
                                title="About Senshudo"
                            >
                                About Senshudo
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.senshudo.tv/branding"
                                rel="noopener noreferrer"
                                target="_blank"
                                title="Branding"
                            >
                                Branding
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.senshudo.tv/team"
                                rel="noopener noreferrer"
                                target="_blank"
                                title="The Team"
                            >
                                The Team
                            </a>
                        </li>
                    </ul>
                </b-col>
                <b-col :sm="12" :md="4">
                    <h4>Legal</h4>
                    <ul class="app--footer__links">
                        <li>
                            <a
                                href="https://www.senshudo.tv/privacy-policy"
                                rel="noopener noreferrer"
                                target="_blank"
                                title="Privacy Policy"
                            >
                                Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.senshudo.tv/terms-of-service"
                                rel="noopener noreferrer"
                                target="_blank"
                                title="Terms of Service"
                            >
                                Terms of Service
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.senshudo.tv/cookie-policy"
                                rel="noopener noreferrer"
                                target="_blank"
                                title="Cookie Policy"
                            >
                                Cookie Policy
                            </a>
                        </li>
                    </ul>
                </b-col>
                <b-col :sm="12" :md="4">
                    <h4>Social</h4>
                    <ul class="social--icons">
                        <li>
                            <a
                                href="https://www.facebook.com/senshudo"
                                target="_blank"
                                title="Facebook"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'facebook-f']" />
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.instagram.com/senshudo"
                                target="_blank"
                                title="Instagram"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'instagram']" />
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://twitter.com/senshudo"
                                target="_blank"
                                title="Twitter"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'twitter']" />
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.youtube.com/channel/UCbicbkPvW-GvMb72mqSm9ag"
                                target="_blank"
                                title="YouTube"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'youtube']" />
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://www.twitch.tv/senshudo"
                                target="_blank"
                                title="Twitch"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'twitch']" />
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://discord.gg/senshudo"
                                target="_blank"
                                title="Discord"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'discord']" />
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://github.com/SenshudoTV/CreativeStreams"
                                target="_blank"
                                title="GitHub"
                                rel="noopener noreferrer"
                            >
                                <font-awesome-icon :icon="['fab', 'github']" />
                            </a>
                        </li>
                    </ul>
                </b-col>
            </b-row>
            <b-row class="app--footer__copyright">
                <b-col class="text-center">
                    <p>&copy; {{ year }} Senshudo Ltd. All Rights Reserved.</p>
                </b-col>
            </b-row>
        </b-container>
    </footer>
</template>

<script>
export default {
    name: 'AppFooter',
    data() {
        const now = new Date()

        return {
            year: now.getFullYear(),
        }
    },
}
</script>
