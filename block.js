const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload, InspectorControls } = wp.editor;
const { TextControl, TextareaControl, Button } = wp.components;

registerBlockType('custom-projects-block/projects', {
    title: 'Projects',
    icon: 'clipboard',
    category: 'common',
    attributes: {
        projects: {
            type: 'array',
            default: [],
        },
    },
    edit: function (props) {
        const { attributes, setAttributes } = props;
        const { projects } = attributes;

        const updateProject = (index, key, value) => {
            const updatedProjects = [...projects];
            updatedProjects[index][key] = value;
            setAttributes({ projects: updatedProjects });
        };

        const addProject = () => {
            const newProject = { title: '', description: '', imageUrl: '', url: '' };
            setAttributes({ projects: [...projects, newProject] });
        };

        const removeProject = (index) => {
            const updatedProjects = [...projects];
            updatedProjects.splice(index, 1);
            setAttributes({ projects: updatedProjects });
        };

        const onSelectImage = (index, media) => {
            updateProject(index, 'imageUrl', media.url);
        };

        const inspectorControls = wp.element.createElement(InspectorControls, null,
            wp.element.createElement('div', { key: 'project-settings' },
                wp.element.createElement(TextControl, {
                    label: 'Project Title',
                    value: projects.length > 0 ? projects[0].title : '',
                    onChange: (value) => updateProject(0, 'title', value),
                }),
                wp.element.createElement(TextareaControl, {
                    label: 'Project Description',
                    value: projects.length > 0 ? projects[0].description : '',
                    onChange: (value) => updateProject(0, 'description', value),
                }),
                wp.element.createElement(TextControl, {
                    label: 'Project URL',
                    value: projects.length > 0 ? projects[0].url : '',
                    onChange: (value) => updateProject(0, 'url', value),
                })
            )
        );

        const projectItems = projects.map((project, index) =>
            wp.element.createElement('div', { key: index, className: 'project-item' },
                wp.element.createElement('h3', null, `Project ${index + 1}`),
                wp.element.createElement(MediaUpload, {
                    onSelect: (media) => onSelectImage(index, media),
                    type: 'image',
                    value: project.imageUrl,
                    render: ({ open }) => (
                        wp.element.createElement(Button, { onClick: open, isPrimary: true },
                            project.imageUrl ? 'Change Image' : 'Select Image'
                        )
                    ),
                }),
                wp.element.createElement(RichText, {
                    placeholder: 'Project Title',
                    value: project.title,
                    onChange: (value) => updateProject(index, 'title', value),
                }),
                wp.element.createElement(RichText, {
                    placeholder: 'Project Description',
                    value: project.description,
                    onChange: (value) => updateProject(index, 'description', value),
                }),
                wp.element.createElement(RichText, {
                    placeholder: 'Project URL',
                    value: project.url,
                    onChange: (value) => updateProject(index, 'url', value),
                }),
                wp.element.createElement(Button, { onClick: () => removeProject(index) }, 'Remove Project')
            )
        );

        return wp.element.createElement('div', { className: 'projects-block' },
            inspectorControls,
            wp.element.createElement('h2', null, 'Projects'),
            projectItems,
            wp.element.createElement(Button, { onClick: addProject }, 'Add Project')
        );
    },
    save: function ({ attributes }) {
        const { projects } = attributes;

        return wp.element.createElement('div', { className: 'projects-block' },
            wp.element.createElement('h2', null, 'Projects'),
            wp.element.createElement('div', { className:'project-items'},
                projects.map((project, index) =>
                    wp.element.createElement('div', { key: index, className: 'project-item' },
                        wp.element.createElement('h3', null, `Project ${index + 1}`),
                        wp.element.createElement('img', { src: project.imageUrl, alt: `Project ${index + 1} Image` }),
                        wp.element.createElement('h4', null, project.title),
                        wp.element.createElement('p', null, project.description),
                        wp.element.createElement('a', { href: project.url }, 'Visit Project'),
                    )
                )
            )
        );
    },
});
