import { Metadata } from "next"
import { LoginForm } from "./_internal"
import Image from "next/image"

export const metadata: Metadata = {
    title: "Welcome to Ainosha",
    description: "Login to your Ainosha account",
}

const LoginPage = () => {
    return (
        <div>
            <div className="relative flex min-h-screen items-center justify-center bg-cover bg-center bg-no-repeat px-4 py-8 sm:px-10 md:px-16">
                <div className="absolute inset-0 z-1 h-full w-full items-center px-5 py-24 [background:radial-gradient(125%_125%_at_50%_10%,#000_40%,#63e_100%)]"></div>
                <div className="relative w-full max-w-[1000px] rounded-md">
                    <div className="relative flex flex-col md:flex-row justify-start items-center rounded-xl bg-white/60 backdrop-blur-lg dark:bg-black/80">
                        <div className="flex-[2] w-full md:w-auto flex items-center justify-start rounded-t-xl md:rounded-l-xl md:rounded-tr-none">
                            <Image
                                src="/assets/images/auth/bg.jpg"
                                alt="Login"
                                width={600}
                                height={600}
                                className="h-[300px] w-full md:h-[550px] md:w-[600px] object-cover rounded-t-xl md:rounded-l-xl md:rounded-tr-none"
                            />
                        </div>

                        {/* Form Sign in nằm bên phải, responsive */}
                        <div className="flex-1 w-full p-6 md:pr-10">
                            <div className="hidden md:block">
                                <div className="flex justify-center mb-4">
                                    <Image
                                        src="/assets/images/auth/logo-ainosha.svg"
                                        alt="Logo"
                                        width={120}
                                        height={120}
                                    />
                                </div>
                            </div>
                            <div className="mb-5 text-center md:text-left">
                                <h1 className="text-3xl md:text-4xl font-extrabold !leading-snug text-primary text-center">
                                    Sign in
                                </h1>
                                <p className="text-base md:text-lg font-medium leading-normal text-white-dark mt-2 text-center">
                                    Sign in to your account to continue.
                                </p>
                            </div>
                            <LoginForm />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default LoginPage
