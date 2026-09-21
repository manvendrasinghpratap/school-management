/**
 * Common Academic Hierarchy
 *
 * Academic Year -> Class -> Section -> Student
 *
 * The component automatically:
 * - uses the current academic year supplied by the Blade component
 * - loads classes when an academic year is selected
 * - loads sections when a class is selected
 * - loads students when a section is selected
 * - clears dependent fields when a parent changes
 * - restores old/edit values when supplied
 */

(function () {
    'use strict';

    function resetSelect(select, placeholder, disabled = true) {
        if (!select) {
            return;
        }

        select.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;

        select.appendChild(option);

        select.value = '';
        select.disabled = disabled;
    }

    function addOption(select, value, label, selected = false) {
        const option = document.createElement('option');

        option.value = value;
        option.textContent = label;
        option.selected = selected;

        select.appendChild(option);
    }

    async function fetchJson(url) {
        const response = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            let message = 'Unable to load academic hierarchy data.';

            try {
                const data = await response.json();
                message = data.message || message;
            } catch (e) {
                // Keep generic message.
            }

            throw new Error(message);
        }

        return response.json();
    }

    async function loadClasses(component, academicYearId, selectedClassId) {
        const classSelect = component.querySelector('[data-class]');
        const sectionSelect = component.querySelector('[data-section]');
        const studentSelect = component.querySelector('[data-student]');

        if (!classSelect) {
            return;
        }

        resetSelect(
            classSelect,
            academicYearId ? 'Loading classes...' : 'Select Academic Year First',
            true
        );

        if (sectionSelect) {
            resetSelect(sectionSelect, 'Select Class First', true);
        }

        if (studentSelect) {
            resetSelect(studentSelect, 'Select Section First', true);
        }

        if (!academicYearId) {
            return;
        }

        const url =
            component.dataset.classesUrl +
            '?academic_year_id=' +
            encodeURIComponent(academicYearId);

        const data = await fetchJson(url);

        const classes = Array.isArray(data.classes)
            ? data.classes
            : [];

        resetSelect(classSelect, 'Select Class', true);

        if (!classes.length) {
            resetSelect(
                classSelect,
                'No active classes found',
                true
            );
            return;
        }

        classes.forEach(function (item) {
            const label =
                item.name +
                (item.code ? ' (' + item.code + ')' : '');

            addOption(
                classSelect,
                item.id,
                label,
                String(item.id) === String(selectedClassId || '')
            );
        });

        classSelect.disabled = false;
    }

    async function loadSections(component, classId, selectedSectionId) {
        const sectionSelect = component.querySelector('[data-section]');
        const studentSelect = component.querySelector('[data-student]');

        if (!sectionSelect) {
            return;
        }

        resetSelect(
            sectionSelect,
            classId ? 'Loading sections...' : 'Select Class First',
            true
        );

        if (studentSelect) {
            resetSelect(studentSelect, 'Select Section First', true);
        }

        if (!classId) {
            return;
        }

        const url =
            component.dataset.sectionsUrl +
            '?class_id=' +
            encodeURIComponent(classId);

        const data = await fetchJson(url);

        const sections = Array.isArray(data.sections)
            ? data.sections
            : [];

        resetSelect(sectionSelect, 'Select Section', true);

        if (!sections.length) {
            resetSelect(
                sectionSelect,
                'No active sections found',
                true
            );
            return;
        }

        sections.forEach(function (item) {
            const label =
                item.name +
                (item.code ? ' (' + item.code + ')' : '');

            addOption(
                sectionSelect,
                item.id,
                label,
                String(item.id) === String(selectedSectionId || '')
            );
        });

        sectionSelect.disabled = false;
    }

    async function loadStudents(
        component,
        academicYearId,
        classId,
        sectionId,
        selectedStudentId
    ) {
        const studentSelect = component.querySelector('[data-student]');

        if (!studentSelect) {
            return;
        }

        resetSelect(
            studentSelect,
            sectionId ? 'Loading students...' : 'Select Section First',
            true
        );

        if (!academicYearId || !classId || !sectionId) {
            return;
        }

        const params = new URLSearchParams({
            academic_year_id: academicYearId,
            class_id: classId,
            section_id: sectionId
        });

        const url =
            component.dataset.studentsUrl +
            '?' +
            params.toString();

        const data = await fetchJson(url);

        const students = Array.isArray(data.students)
            ? data.students
            : [];

        resetSelect(studentSelect, 'Select Student', true);

        if (!students.length) {
            resetSelect(
                studentSelect,
                'No active students found',
                true
            );
            return;
        }

        students.forEach(function (student) {
            const parts = [
                student.first_name,
                student.middle_name,
                student.last_name
            ].filter(Boolean);

            let label = parts.join(' ');

            if (student.student_number) {
                label += ' (' + student.student_number + ')';
            }

            addOption(
                studentSelect,
                student.id,
                label,
                String(student.id) === String(selectedStudentId || '')
            );
        });

        studentSelect.disabled = false;
    }

    async function initialise(component) {
        const academicYearSelect =
            component.querySelector('[data-academic-year]');

        const classSelect =
            component.querySelector('[data-class]');

        const sectionSelect =
            component.querySelector('[data-section]');

        const studentSelect =
            component.querySelector('[data-student]');

        const initialAcademicYear =
            component.dataset.selectedAcademicYear || '';

        const initialClass =
            component.dataset.selectedClass || '';

        const initialSection =
            component.dataset.selectedSection || '';

        const initialStudent =
            component.dataset.selectedStudent || '';

        if (academicYearSelect && initialAcademicYear) {
            academicYearSelect.value = initialAcademicYear;
        }

        if (academicYearSelect) {
            academicYearSelect.addEventListener(
                'change',
                async function () {
                    try {
                        await loadClasses(
                            component,
                            this.value,
                            null
                        );
                    } catch (error) {
                        console.error(
                            'Academic hierarchy class loading failed:',
                            error
                        );
                        resetSelect(
                            classSelect,
                            'Unable to load classes',
                            true
                        );
                    }
                }
            );
        }

        if (classSelect) {
            classSelect.addEventListener(
                'change',
                async function () {
                    try {
                        await loadSections(
                            component,
                            this.value,
                            null
                        );
                    } catch (error) {
                        console.error(
                            'Academic hierarchy section loading failed:',
                            error
                        );
                        resetSelect(
                            sectionSelect,
                            'Unable to load sections',
                            true
                        );
                    }
                }
            );
        }

        if (sectionSelect) {
            sectionSelect.addEventListener(
                'change',
                async function () {
                    try {
                        await loadStudents(
                            component,
                            academicYearSelect
                                ? academicYearSelect.value
                                : initialAcademicYear,
                            classSelect
                                ? classSelect.value
                                : initialClass,
                            this.value,
                            null
                        );
                    } catch (error) {
                        console.error(
                            'Academic hierarchy student loading failed:',
                            error
                        );
                        resetSelect(
                            studentSelect,
                            'Unable to load students',
                            true
                        );
                    }
                }
            );
        }

        /*
         * Initial loading.
         *
         * This is important: when the current academic year is already
         * selected by the server, classes are loaded automatically.
         */
        if (initialAcademicYear && classSelect) {
            try {
                await loadClasses(
                    component,
                    initialAcademicYear,
                    initialClass
                );

                if (
                    initialClass &&
                    classSelect.value &&
                    sectionSelect
                ) {
                    await loadSections(
                        component,
                        initialClass,
                        initialSection
                    );
                }

                if (
                    initialAcademicYear &&
                    initialClass &&
                    initialSection &&
                    sectionSelect &&
                    sectionSelect.value &&
                    studentSelect
                ) {
                    await loadStudents(
                        component,
                        initialAcademicYear,
                        initialClass,
                        initialSection,
                        initialStudent
                    );
                }
            } catch (error) {
                console.error(
                    'Academic hierarchy initialization failed:',
                    error
                );
            }
        }
    }

    function initialiseAll() {
        document
            .querySelectorAll('[data-academic-hierarchy]')
            .forEach(function (component) {
                initialise(component);
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initialiseAll
        );
    } else {
        initialiseAll();
    }
})();
