import "@uiw/react-md-editor/markdown-editor.css"

import MDEditor from "@uiw/react-md-editor"

export default function MarkdownEditor({
    content,
    classname,
    onChange,
}: {
    content: string
    classname?: string
    onChange: (value: string) => void
}) {
    return (
        <div data-color-mode="dark">
            <MDEditor
                value={content}
                onChange={(val) => onChange(val || "")}
                className={classname}
            />
        </div>
    )
}
