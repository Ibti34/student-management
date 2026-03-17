<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
// Fixed: Added 'type' for TwoFactorConfigContent to help the TS linter
import { computed, ref, type Ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import AuthLayout from '@/layouts/AuthLayout.vue';
import type { TwoFactorConfigContent } from '@/types';

// Declare route function to stop TS complaining it's missing
declare function route(name: string): string;

const form = useForm({
    code: '',
    recovery_code: '',
});

const showRecoveryInput = ref<boolean>(false);

const authConfigContent = computed<TwoFactorConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Recovery Code',
            description: 'Please confirm access to your account by entering one of your emergency recovery codes.',
            buttonText: 'login using an authentication code',
        };
    }

    return {
        title: 'Authentication Code',
        description: 'Enter the authentication code provided by your authenticator application.',
        buttonText: 'login using a recovery code',
    };
});

const toggleRecoveryMode = (): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    form.clearErrors();
    form.reset();
};

const submit = () => {
    form.post(route('two-factor.login'), {
        onFinish: () => form.reset('code', 'recovery_code'),
    });
};
</script>

<template>
    <AuthLayout
        :title="authConfigContent.title"
        :description="authConfigContent.description"
    >
        <Head title="Two-Factor Authentication" />

        <div class="space-y-6">
            <form @submit.prevent="submit" class="space-y-4">
                
                <template v-if="!showRecoveryInput">
                    <div class="flex flex-col items-center justify-center space-y-3 text-center">
                        <div class="flex w-full items-center justify-center">
                            <InputOTP
                                id="otp"
                                v-model="form.code"
                                :maxlength="6"
                                :disabled="form.processing"
                                autofocus
                            >
                                <InputOTPGroup>
                                    <InputOTPSlot
                                        v-for="index in 6"
                                        :key="index"
                                        :index="index - 1"
                                    />
                                </InputOTPGroup>
                            </InputOTP>
                        </div>
                        <InputError :message="form.errors.code" />
                    </div>
                </template>

                <template v-else>
                    <div class="space-y-2">
                        <Input
                            v-model="form.recovery_code"
                            type="text"
                            placeholder="Enter recovery code"
                            :autofocus="showRecoveryInput"
                            required
                        />
                        <InputError :message="form.errors.recovery_code" />
                    </div>
                </template>

                <Button type="submit" class="w-full" :disabled="form.processing">
                    {{ form.processing ? 'Verifying...' : 'Continue' }}
                </Button>

                <div class="text-center text-sm text-muted-foreground">
                    <span>or you can </span>
                    <button
                        type="button"
                        class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current!"
                        @click="toggleRecoveryMode"
                    >
                        {{ authConfigContent.buttonText }}
                    </button>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>