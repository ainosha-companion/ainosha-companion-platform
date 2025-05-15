"use client"
import { showAlert } from "@/app/_internal/utils/toast"
import http from "@/client/integration/http"
import { BtnLoading } from "@/core/components/btnLoading"
import { GetCategoriesSchema } from "@/server/article/category/_internal/schema"
import { Category } from "@/server/article/category/_internal/type"
import { ArticleRequestSchema } from "@/server/article/request/_internal/schema"
import { ArticleRequest } from "@/server/article/request/_internal/type"
import { Button } from "@headlessui/react"
import { valibotResolver } from "@hookform/resolvers/valibot"
import { useEffect, useState } from "react"
import { useForm } from "react-hook-form"
import { BsStars } from "react-icons/bs"
import { IoCloseSharp } from "react-icons/io5"
import useSWRMutation from "swr/mutation"
import * as v from "valibot"
import { API_ENDPOINT } from "../../_internal/endpoints"
const defaultValueForm = {
    topic: "",
    deep_research: false,
    style: "",
    target_audience: "",
    category_id: null,
    minimum_words: 100,
    maximum_words: 1000,
    number_of_chapters: 1,
    introduction_length: 50,
    conclusion_length: 50,
}

interface CreateArticleModalProps {
    classname?: string
}

const CreateArticleModal: React.FC<CreateArticleModalProps> = ({
    classname,
}) => {
    const [isModalOpen, setIsModalOpen] = useState(false)
    const [referenceArticles, setReferenceArticles] = useState<string[]>([])
    const [metaKeywords, setMetaKeywords] = useState<string[]>([])
    const [categories, setCategories] = useState<Category[]>([])

    const {
        register,
        handleSubmit,
        reset,
        formState: { errors },
    } = useForm<any>({
        resolver: valibotResolver(ArticleRequestSchema),
        defaultValues: defaultValueForm,
    })

    useEffect(() => {
        if (isModalOpen) {
            document.body.style.overflow = "hidden"
        } else {
            document.body.style.overflow = ""
        }

        return () => {
            document.body.style.overflow = ""
        }
    }, [isModalOpen])
    console.log("errors", errors)
    useEffect(() => {
        const fetchCategories = async () => {
            try {
                const res = await http.get(API_ENDPOINT.GetCategories).json()
                const parsed = v.parse(GetCategoriesSchema, res)
                setCategories(parsed.result.categories)
            } catch (error) {
                console.log("fetch categories error:", error)
                return []
            }
        }
        fetchCategories()
    }, [])

    const openModal = () => setIsModalOpen(true)
    const closeModal = () => setIsModalOpen(false)

    const { trigger, isMutating } = useSWRMutation<
        unknown,
        Error,
        string,
        ArticleRequest
    >(
        API_ENDPOINT.ArticleRequest,
        (_, { arg }) => http.post(API_ENDPOINT.ArticleRequest, { json: arg }),
        {
            onSuccess: async () => {
                showAlert({
                    icon: "success",
                    title: "Article added to queue.",
                })
                setIsModalOpen(false)
                setReferenceArticles([])
                setMetaKeywords([])
                reset(defaultValueForm)
            },
            onError: async () => {
                showAlert({
                    icon: "error",
                    title: "Failed to add article to queue.",
                })
                setIsModalOpen(false)
            },
        },
    )

    const handleAddChip = (
        value: string,
        list: string[],
        setList: React.Dispatch<React.SetStateAction<string[]>>,
    ) => {
        const trimmed = value.trim()
        if (trimmed && !list.includes(trimmed)) {
            setList([...list, trimmed])
        }
    }

    const handleRemoveChip = (
        value: string,
        setList: React.Dispatch<React.SetStateAction<string[]>>,
    ) => {
        setList((prev) => prev.filter((item) => item !== value))
    }

    const submit = (data: ArticleRequest) => {
        const finalData: ArticleRequest = {
            ...data,
            reference_articles: referenceArticles,
            meta_keywords: metaKeywords,
        }
        trigger(finalData)
    }

    return (
        <>
            <Button
                onClick={openModal}
                className={`flex items-center gap-2 bg-gradient-to-r from-primary to-gray-300 to- text-black font-bold py-2 px-6 rounded-lg hover:opacity-90 ${classname}`}
            >
                <BsStars size={16} />
                <span className="sm:block hidden">Ainosha Agent</span>
            </Button>

            {isModalOpen && (
                <div
                    onClick={closeModal}
                    className="fixed inset-0 bg-black/50 flex justify-center items-center z-50 p-4 overflow-y-auto"
                >
                    <div
                        onClick={(e) => e.stopPropagation()}
                        className="bg-[#1a2332] text-white p-6 sm:p-8 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl relative animate-fadeIn"
                    >
                        <button
                            onClick={closeModal}
                            className="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl"
                        >
                            <IoCloseSharp className="w-5 h-5" />
                        </button>

                        <h2 className="text-2xl font-bold mb-6 text-center">
                            Ainosha Agent
                        </h2>

                        <form
                            onSubmit={handleSubmit(submit)}
                            className="space-y-5"
                        >
                            {/* Topic */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Topic{" "}
                                    <span className="text-red-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    {...register("topic")}
                                    required
                                    className="border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                />
                            </div>

                            {/* Deep Research */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Deep Research{" "}
                                    <span className="text-red-400">*</span>
                                </label>
                                <select
                                    {...register("deep_research", {
                                        setValueAs: (v) => v === "true",
                                    })}
                                    className="appearance-none border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                >
                                    <option
                                        value="true"
                                        disabled
                                        className="bg-gray-800"
                                    >
                                        Yes
                                    </option>
                                    <option
                                        value="false"
                                        className="bg-gray-800"
                                    >
                                        No
                                    </option>
                                </select>
                            </div>

                            {/* Style */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Style{" "}
                                    <span className="text-red-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    {...register("style")}
                                    required
                                    placeholder="Ex: Formal, Friendly, Professional"
                                    className="border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                />
                            </div>

                            {/* Target Audience */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Target Audience{" "}
                                    <span className="text-red-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    {...register("target_audience")}
                                    required
                                    placeholder="Ex: Beginner, Investor, Expert"
                                    className="border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                />
                            </div>

                            {/* Category ID */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Category{" "}
                                    <span className="text-red-400">*</span>
                                </label>
                                <select
                                    {...register("category_id", {
                                        valueAsNumber: true,
                                    })}
                                    required
                                    className="appearance-none border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                >
                                    <option
                                        value=""
                                        disabled
                                        selected
                                        hidden
                                        className="text-gray-300"
                                    >
                                        Select a category
                                    </option>
                                    {categories.map((category) => (
                                        <option
                                            key={category.id}
                                            value={category.id}
                                            className="bg-gray-800 text-white"
                                        >
                                            {category.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            {/* Word Counts */}
                            {[
                                "minimum_words",
                                "maximum_words",
                                "number_of_chapters",
                                "introduction_length",
                                "conclusion_length",
                            ].map((field, idx) => (
                                <div key={idx}>
                                    <label className="block font-semibold mb-1 text-gray-300 capitalize">
                                        {field.replace(/_/g, " ")}{" "}
                                        <span className="text-red-400">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        {...register(
                                            field as keyof ArticleRequest,
                                            { valueAsNumber: true },
                                        )}
                                        min={0}
                                        className="border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                    />
                                </div>
                            ))}

                            {/* Reference Articles */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Reference Articles (press Enter to add)
                                </label>
                                <div className="border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg flex flex-wrap gap-2 focus-within:ring-2 focus-within:ring-primary">
                                    {referenceArticles.map((url, idx) => (
                                        <div
                                            key={idx}
                                            className="flex w-full bg-gray-500/20 text-gray-300 px-2 py-1 rounded-full items-center gap-1"
                                        >
                                            <span className="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                {url}
                                            </span>
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    handleRemoveChip(
                                                        url,
                                                        setReferenceArticles,
                                                    )
                                                }
                                            >
                                                <IoCloseSharp className="w-4 h-4" />
                                            </button>
                                        </div>
                                    ))}
                                    <input
                                        type="text"
                                        onKeyDown={(e) => {
                                            if (e.key === "Enter") {
                                                e.preventDefault()
                                                handleAddChip(
                                                    e.currentTarget.value,
                                                    referenceArticles,
                                                    setReferenceArticles,
                                                )
                                                e.currentTarget.value = ""
                                            }
                                        }}
                                        className="bg-transparent text-white focus:outline-none flex-1 min-w-[100px]"
                                    />
                                </div>
                            </div>

                            {/* Meta Keywords */}
                            <div>
                                <label className="block font-semibold mb-1 text-gray-300">
                                    Meta Keywords (press Enter to add)
                                </label>
                                <div className="border border-gray-600 bg-transparent text-white p-3 w-full rounded-lg flex flex-wrap gap-2 focus-within:ring-2 focus-within:ring-primary">
                                    {metaKeywords.map((word, idx) => (
                                        <div
                                            key={idx}
                                            className="bg-gray-500/20 text-gray-300 px-2 py-1 rounded-full flex items-center gap-1"
                                        >
                                            <span>{word}</span>
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    handleRemoveChip(
                                                        word,
                                                        setMetaKeywords,
                                                    )
                                                }
                                            >
                                                <IoCloseSharp className="w-4 h-4" />
                                            </button>
                                        </div>
                                    ))}
                                    <input
                                        type="text"
                                        onKeyDown={(e) => {
                                            if (e.key === "Enter") {
                                                e.preventDefault()
                                                handleAddChip(
                                                    e.currentTarget.value,
                                                    metaKeywords,
                                                    setMetaKeywords,
                                                )
                                                e.currentTarget.value = ""
                                            }
                                        }}
                                        className="bg-transparent text-white focus:outline-none flex-1 min-w-[100px]"
                                    />
                                </div>
                            </div>

                            {/* Buttons */}
                            <div className="flex justify-end gap-4 pt-6">
                                <Button
                                    type="button"
                                    onClick={closeModal}
                                    className="bg-gray-700 text-white hover:bg-gray-600 py-2 px-5 rounded-lg"
                                >
                                    Cancel
                                </Button>
                                <Button
                                    type="submit"
                                    className="bg-gradient-to-r from-primary to-gray-300 text-black font-bold py-2 px-6 rounded-lg hover:opacity-90"
                                >
                                    {isMutating ? <BtnLoading /> : "Generate"}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    )
}

export default CreateArticleModal
