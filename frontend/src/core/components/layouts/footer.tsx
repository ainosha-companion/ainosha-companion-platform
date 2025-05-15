import Image from "next/image"
import { FaMailBulk, FaMapPin, FaPhone } from "react-icons/fa"
import { FaEarthAsia, FaXTwitter } from "react-icons/fa6"

export default function Footer() {
    return (
        <footer className="bg-black text-white border-t-[0.5px] border-primary/30">
            {/* Main Footer Content */}
            <div className="container mx-auto px-6 pt-10 ">
                <div className="flex flex-wrap">
                    {/* Company Info Section with Logo */}
                    <div className="w-full md:w-1/3 mb-8">
                        <div className="mb-4 flex items-center">
                            <div className="horizontal-logo flex items-center justify-start py-2">
                                <div className="main-logo flex items-center gap-2 transition-all duration-300 hover:opacity-80">
                                    <Image
                                        className="h-10 w-10 rounded-[100px]  object-contain"
                                        src="/assets/images/auth/logo-ainosha.svg"
                                        alt="Ainosha logo"
                                        width={40}
                                        height={40}
                                    />

                                    <span className="hidden bg-gradient-to-r from-[#01FFFF] to-[#D205D9] bg-clip-text text-xl font-bold tracking-wide text-transparent sm:block">
                                        Ainosha
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p className="mb-6 text-gray-300">
                            Ainosha builds AI-powered companions for traders,
                            delivering real-time analytics, strategy
                            optimization, and smarter decision-making for market
                            success.
                        </p>
                        {/* Social Media Links */}
                        <div className="flex space-x-4 mb-6">
                            <a
                                href="https://x.com/AinoshaAI"
                                className="text-gray-300 hover:text-primary transition duration-300 bg-[#1B252E] p-3 rounded-full"
                            >
                                <FaXTwitter size={20} />
                            </a>
                        </div>
                    </div>

                    {/* Quick Links Section */}
                    <div className="w-full md:w-1/3 mb-8"></div>

                    {/* Contact Info Section */}
                    <div className="w-full md:w-1/3 mb-8">
                        <h3 className="text-lg font-semibold mb-6 text-primary">
                            Contact Us
                        </h3>
                        <ul className="space-y-4">
                            <li className="flex items-start">
                                <FaMapPin
                                    size={18}
                                    className="mr-3 mt-1 text-primary"
                                />
                                <span className="text-gray-300">Address</span>
                            </li>
                            <li className="flex items-center">
                                <FaPhone
                                    size={18}
                                    className="mr-3 text-primary"
                                />
                                <span className="text-gray-300">Phone</span>
                            </li>
                            <li className="flex items-center">
                                <FaMailBulk
                                    size={18}
                                    className="mr-3 text-primary"
                                />
                                <span className="text-gray-300">
                                    ainoshaai@gmail.com
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {/* Copyright Section */}
            <div className="bg-gray-800 py-4">
                <div className="container mx-auto px-6">
                    <div className="flex flex-col md:flex-row justify-between items-center">
                        <p className="text-sm text-gray-400 mb-4 md:mb-0">
                            © {new Date().getFullYear()} Ainosha AI. All rights
                            reserved.
                        </p>
                        <div className="flex space-x-6">
                            <a
                                href="#"
                                className="text-sm text-gray-400 hover:text-primary transition duration-300"
                            >
                                Privacy Policy
                            </a>
                            <a
                                href="#"
                                className="text-sm text-gray-400 hover:text-primary transition duration-300"
                            >
                                Terms of Service
                            </a>
                            <a
                                href="#"
                                className="text-sm text-gray-400 hover:text-primary transition duration-300"
                            >
                                Cookie Policy
                            </a>
                        </div>
                        <div className="flex items-center ml-4">
                            <FaEarthAsia
                                size={18}
                                className="mr-2 text-gray-400"
                            />
                            <span className="text-gray-400">English (US)</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    )
}
