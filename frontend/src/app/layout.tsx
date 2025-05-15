import { AuthProvider } from "@/client/auth"
import ProviderComponent from "@/core/components/layouts/provider-component"
import "@/styles/tailwind.css"
import clsx from "clsx"
import { Metadata } from "next"
import { Nunito } from "next/font/google"
import "react-perfect-scrollbar/dist/css/styles.css"
import { SWRConfig } from "swr"

export const metadata: Metadata = {
    title: {
        template: "%s | Meet your AI agents",
        default: "AINOSHA - Meet your AI agents",
    },
    icons: "/assets/images/auth/logo-ainosha.svg",
}
const nunito = Nunito({
    weight: ["400", "500", "600", "700", "800"],
    subsets: ["latin"],
    display: "swap",
    variable: "--font-nunito",
})

export default function RootLayout({
    children,
}: {
    children: React.ReactNode
}) {
    return (
        <html lang="en" className="bg-[#1C2625]">
            <body
                className={`${clsx(nunito.variable, "dark")} overscroll-none`}
            >
                <SWRConfig
                    value={{
                        errorRetryCount: 0,
                        revalidateOnFocus: false,
                    }}
                >
                    <AuthProvider>
                        <ProviderComponent>{children}</ProviderComponent>
                    </AuthProvider>
                </SWRConfig>
            </body>
        </html>
    )
}
