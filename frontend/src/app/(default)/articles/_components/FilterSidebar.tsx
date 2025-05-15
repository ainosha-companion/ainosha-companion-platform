"use client"

import { cn } from "@/app/_internal/utils/style"
import LoadingAnimation from "@/core/components/common/loadingAnimation"
import { useAppDispatch } from "@/core/hooks/useRedux"
import {
    updateAuthorName,
    updateCategoryName,
    updateState,
    updateTagName,
} from "@/store/reducer/articlesSlice"
import { Checkbox } from "@headlessui/react"
import { useMemo, useState } from "react"
import { FaChevronDown, FaChevronUp, FaSearch, FaTimes } from "react-icons/fa"
import { FaCheck } from "react-icons/fa6"
import { IoCloseSharp } from "react-icons/io5"
import useFilterOptions from "../_internal/useFilterOptions"

type Props = {
    isVisible: boolean
    windowWidth: number
    toggleFilter: () => void
    handleOverlayClick: () => void
    isShowAuthors?: boolean
}

export default function FilterSidebar({
    isVisible,
    windowWidth,
    isShowAuthors = true,
    handleOverlayClick,
    toggleFilter,
}: Props) {
    const {
        categories,
        tags,
        authors,
        isAuthorsLoading,
        isCategoriesLoading,
        isTagsLoading,
        categoryName,
        tagName,
        authorName,
        searchQuery,
        setSearchQuery,
    } = useFilterOptions()
    const dispatch = useAppDispatch()
    const [showCategories, setShowCategories] = useState(false)
    const [showAuthors, setShowAuthors] = useState(false)
    const [showTags, setShowTags] = useState(false)

    // Search states for each filter section
    const [categorySearch, setCategorySearch] = useState("")
    const [authorSearch, setAuthorSearch] = useState("")
    const [tagSearch, setTagSearch] = useState("")

    // Filtered lists based on search queries
    const filteredCategories = useMemo(() => {
        if (!categorySearch) return categories
        return categories.filter((cat) =>
            cat.name.toLowerCase().includes(categorySearch.toLowerCase()),
        )
    }, [categories, categorySearch])

    const filteredAuthors = useMemo(() => {
        if (!authorSearch) return authors
        return authors.filter((author) =>
            author.name.toLowerCase().includes(authorSearch.toLowerCase()),
        )
    }, [authors, authorSearch])

    const filteredTags = useMemo(() => {
        if (!tagSearch) return tags
        return tags.filter((tag) =>
            tag.name.toLowerCase().includes(tagSearch.toLowerCase()),
        )
    }, [tags, tagSearch])

    return (
        <>
            {isVisible && windowWidth < 768 && (
                <div
                    className="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm"
                    onClick={handleOverlayClick}
                />
            )}

            <aside
                className={cn(
                    "fixed rounded-md left-0 top-0 z-40 h-full w-full max-w-xs transform bg-[#111827]  shadow-xl transition-transform duration-300 ease-in-out md:static md:z-auto md:col-span-4 md:h-screen md:max-w-none md:translate-x-0 md:border-r md:border-gray-800 lg:col-span-3",
                    isVisible ? "translate-x-0" : "-translate-x-full",
                )}
            >
                <div className="flex items-center justify-between border-b  border-gray-800 px-4 py-4 md:hidden">
                    <h2 className="text-lg font-semibold tracking-wide">
                        Filters
                    </h2>
                    <button
                        onClick={toggleFilter}
                        className="rounded-full bg-gray-700 p-2 transition hover:bg-gray-600"
                        aria-label="Close filter"
                    >
                        <FaTimes size={16} />
                    </button>
                </div>
                <div className="hidden md:block">
                    <h2 className="flex flex-col  tracking-wide text-white px-6 py-2">
                        <div className="items-center text-lg font-bold gap-2 tracking-wide text-white py-2 mt-2">
                            Filter Articles
                        </div>
                    </h2>
                </div>

                <div className="scrollbar-thin space-y-6 overflow-y-auto px-4 py-1 md:px-6 max-h-[calc(100vh-4rem)]">
                    {/* Search */}
                    <div className="relative">
                        <FaSearch
                            className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                            size={14}
                        />
                        <input
                            type="text"
                            placeholder="Search articles..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="w-full rounded-lg bg-gray-800 py-4 pl-10 pr-10 text-sm text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-primary"
                        />
                        {searchQuery && (
                            <IoCloseSharp
                                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-primary"
                                size={18}
                                onClick={() => setSearchQuery("")}
                            />
                        )}
                    </div>

                    {/* Categories */}
                    <FilterSection
                        title="Categories"
                        isOpen={showCategories}
                        toggle={() => setShowCategories(!showCategories)}
                        isFilterAll={categoryName.length > 0}
                        onDeSelectAll={() =>
                            dispatch(updateState({ categoryName: [] }))
                        }
                        onSelectAll={() =>
                            dispatch(
                                updateState({
                                    categoryName: categories.map(
                                        (cat) => cat.name,
                                    ),
                                }),
                            )
                        }
                    >
                        {showCategories && (
                            <div className="relative mt-3 mb-3 flex items-center justify-center">
                                <FaSearch
                                    className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 ml-1"
                                    size={12}
                                />
                                <input
                                    type="text"
                                    placeholder="Search categories..."
                                    value={categorySearch}
                                    onChange={(e) =>
                                        setCategorySearch(e.target.value)
                                    }
                                    className="w-[98%] rounded-lg bg-gray-700 py-2 pl-9 pr-9 text-xs text-white placeholder-gray-400 outline-none focus:ring-1 focus:ring-primary"
                                />
                                {categorySearch && (
                                    <IoCloseSharp
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-primary p-2"
                                        size={14}
                                        onClick={() => setCategorySearch("")}
                                    />
                                )}
                            </div>
                        )}
                        <div className="mt-3 space-y-2 ">
                            {isCategoriesLoading ? (
                                <div className="flex justify-center items-center min-h-[100px]">
                                    <LoadingAnimation />
                                </div>
                            ) : filteredCategories.length === 0 ? (
                                <span className="text-sm text-gray-400">
                                    No categories found
                                </span>
                            ) : (
                                filteredCategories.map((cat) => (
                                    <label
                                        key={cat.name}
                                        className="flex items-center gap-2 text-sm text-white"
                                    >
                                        <Checkbox
                                            checked={
                                                categoryName.length === 0 &&
                                                cat.name === "All"
                                                    ? true
                                                    : categoryName.includes(
                                                          cat.name,
                                                      )
                                            }
                                            onChange={() =>
                                                dispatch(
                                                    updateCategoryName(
                                                        cat.name,
                                                    ),
                                                )
                                            }
                                            className="group size-5 rounded-md bg-white/10 p-1 ring-1 ring-white/15 ring-inset data-[checked]:bg-primary"
                                        >
                                            <FaCheck className="hidden size-3 fill-black group-data-[checked]:block" />
                                        </Checkbox>
                                        {cat.name}
                                    </label>
                                ))
                            )}
                        </div>
                    </FilterSection>

                    {/* Authors */}
                    {isShowAuthors && (
                        <FilterSection
                            title="Authors"
                            isOpen={showAuthors}
                            toggle={() => setShowAuthors(!showAuthors)}
                            isFilterAll={authorName.length > 0}
                            onDeSelectAll={() =>
                                dispatch(updateState({ authorName: [] }))
                            }
                            onSelectAll={() =>
                                dispatch(
                                    updateState({
                                        authorName: authors.map(
                                            (author) => author.name,
                                        ),
                                    }),
                                )
                            }
                        >
                            {showAuthors && (
                                <div className="relative mt-3 mb-3 flex items-center justify-center">
                                    <FaSearch
                                        className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 ml-1"
                                        size={12}
                                    />
                                    <input
                                        type="text"
                                        placeholder="Search authors..."
                                        value={authorSearch}
                                        onChange={(e) =>
                                            setAuthorSearch(e.target.value)
                                        }
                                        className="w-[98%] rounded-lg bg-gray-700 py-2 pl-9 pr-9 text-xs text-white placeholder-gray-400 outline-none focus:ring-1 focus:ring-primary"
                                    />
                                    {authorSearch && (
                                        <IoCloseSharp
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-primary"
                                            size={14}
                                            onClick={() => setAuthorSearch("")}
                                        />
                                    )}
                                </div>
                            )}
                            <div className="mt-3 space-y-3">
                                {isAuthorsLoading ? (
                                    <div className="flex justify-center items-center min-h-[100px]">
                                        <LoadingAnimation />
                                    </div>
                                ) : filteredAuthors.length === 0 ? (
                                    <span className="text-sm text-gray-400">
                                        No authors found
                                    </span>
                                ) : (
                                    filteredAuthors.map((author) => (
                                        <label
                                            key={author.name}
                                            className="flex items-center gap-3 text-sm text-white"
                                        >
                                            <Checkbox
                                                checked={
                                                    authorName.length === 0 &&
                                                    author.name === "All"
                                                        ? true
                                                        : authorName.includes(
                                                              author.name,
                                                          )
                                                }
                                                onChange={() =>
                                                    dispatch(
                                                        updateAuthorName(
                                                            author.name,
                                                        ),
                                                    )
                                                }
                                                className="group size-5 rounded-md bg-white/10 p-1 ring-1 ring-white/15 ring-inset data-[checked]:bg-primary"
                                            >
                                                <FaCheck className="hidden size-3 fill-black group-data-[checked]:block" />
                                            </Checkbox>

                                            <span>{author.name}</span>
                                        </label>
                                    ))
                                )}
                            </div>
                        </FilterSection>
                    )}

                    {/* Tags */}
                    <FilterSection
                        title="Tags"
                        isOpen={showTags}
                        toggle={() => setShowTags(!showTags)}
                        isFilterAll={tagName.length > 0}
                        onDeSelectAll={() =>
                            dispatch(updateState({ tagName: [] }))
                        }
                        onSelectAll={() =>
                            dispatch(
                                updateState({
                                    tagName: tags.map((tag) => tag.name),
                                }),
                            )
                        }
                    >
                        {showTags && (
                            <div className="relative mt-3 mb-3 flex items-center justify-center">
                                <FaSearch
                                    className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 ml-1"
                                    size={12}
                                />
                                <input
                                    type="text"
                                    placeholder="Search tags..."
                                    value={tagSearch}
                                    onChange={(e) =>
                                        setTagSearch(e.target.value)
                                    }
                                    className="w-[98%] rounded-lg bg-gray-700 py-2 pl-9 pr-9 text-xs text-white placeholder-gray-400 outline-none focus:ring-1 focus:ring-primary"
                                />
                                {tagSearch && (
                                    <IoCloseSharp
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-primary"
                                        size={14}
                                        onClick={() => setTagSearch("")}
                                    />
                                )}
                            </div>
                        )}
                        <div className="mt-3 flex flex-wrap gap-2 scrollbar-thin overflow-y-auto max-h-[300px]">
                            {isTagsLoading ? (
                                <div className="flex justify-center items-center min-h-[100px]">
                                    <LoadingAnimation />
                                </div>
                            ) : filteredTags.length === 0 ? (
                                <span className="text-sm text-gray-400">
                                    No tags found
                                </span>
                            ) : (
                                filteredTags.map((tag) => {
                                    const isSelected = tagName.includes(
                                        tag.name,
                                    )
                                    return (
                                        <button
                                            key={tag.name}
                                            onClick={() =>
                                                dispatch(
                                                    updateTagName(tag.name),
                                                )
                                            }
                                            className={cn(
                                                "relative flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium transition-all duration-200",
                                                isSelected
                                                    ? "border-primary bg-primary/10 text-primary shadow-inner"
                                                    : "border-gray-600 text-gray-200 hover:border-primary hover:bg-gray-700/40 hover:text-primary",
                                            )}
                                        >
                                            {isSelected && (
                                                <span className="inline-block h-2 w-2 rounded-full bg-primary" />
                                            )}
                                            # {tag.name}
                                        </button>
                                    )
                                })
                            )}
                        </div>
                    </FilterSection>
                </div>
            </aside>
        </>
    )
}

function FilterSection({
    title,
    isOpen,
    isFilterAll,
    toggle,
    onDeSelectAll,
    onSelectAll,
    children,
}: {
    title: string
    isOpen: boolean
    isFilterAll: boolean
    toggle: () => void
    onDeSelectAll?: () => void
    onSelectAll?: () => void
    children: React.ReactNode
}) {
    return (
        <div className="rounded-xl bg-gray-800 p-4 shadow-inner">
            <div
                className="flex cursor-pointer items-center justify-between"
                onClick={toggle}
            >
                <h3 className="flex text-sm font-semibold tracking-wide text-gray-300">
                    {title} {!isOpen && !isFilterAll && "(All)"}
                </h3>

                <div className="flex items-center gap-2">
                    {isFilterAll ? (
                        <button
                            className="text-sm text-blue-400 hover:underline"
                            onClick={(e) => {
                                e.stopPropagation()
                                onDeSelectAll && onDeSelectAll()
                            }}
                        >
                            Deselect All
                        </button>
                    ) : (
                        isOpen && (
                            <button
                                onClick={(e) => {
                                    e.stopPropagation()
                                    onSelectAll && onSelectAll()
                                }}
                                className="text-sm text-blue-400 hover:underline"
                            >
                                Select All
                            </button>
                        )
                    )}
                    {isOpen ? (
                        <FaChevronUp className="text-gray-400" />
                    ) : (
                        <FaChevronDown className="text-gray-400" />
                    )}
                </div>
            </div>

            <div
                className={`transition-all duration-300 overflow-hidden ${
                    isOpen ? "max-h-96 opacity-100" : "max-h-0 opacity-0"
                }`}
            >
                {children}
            </div>
        </div>
    )
}
