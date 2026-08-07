import { computed } from "vue";

export function useStudentPhoto() {
    const getDefaultPhoto = (gender) => {
        return gender?.toLowerCase() === "female"
            ? "/images/default-female.jpg"
            : "/images/default-male.jpg";
    };

    const getPhotoUrl = (student) => {
        if (student?.photo?.url) {
            return student.photo.url;
        }

        return getDefaultPhoto(student?.gender);
    };

    const handlePhotoError = (event, gender) => {
        event.target.src = getDefaultPhoto(gender);
    };

    return {
        getDefaultPhoto,
        getPhotoUrl,
        handlePhotoError,
    };
}