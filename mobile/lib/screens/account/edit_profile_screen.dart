import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'dart:io';
import '../../../utils/navigation_helper.dart';
import '../../config/theme.dart';
import '../../providers/profile_provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:image_picker/image_picker.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/animations/blur_fade.dart';

class EditProfileScreen extends StatefulWidget {
  const EditProfileScreen({super.key});

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final _formKey = GlobalKey<FormState>();

  late TextEditingController _nameController;
  late TextEditingController _emailController;
  late TextEditingController _phoneController;

  final ImagePicker _picker = ImagePicker();
  File? _selectedImage;
  bool _isUploadingAvatar = false;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController();
    _emailController = TextEditingController();
    _phoneController = TextEditingController();

    // Fetch profile data and populate controllers
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      await context.read<ProfileProvider>().fetchProfile();
      if (mounted) {
        final user = context.read<ProfileProvider>().user;
        _nameController.text = user?.name ?? '';
        _emailController.text = user?.email ?? '';
        _phoneController.text = user?.phone ?? '';
      }
    });
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    super.dispose();
  }

  void _saveProfile() async {
    if (_formKey.currentState!.validate()) {
      final success = await context.read<ProfileProvider>().updateProfile(
        name: _nameController.text.trim(),
        email: _emailController.text.trim(),
        phone: _phoneController.text.trim(),
      );

      if (success) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Profil berhasil diperbarui')),
          );
          context.popOrGoHome();
        }
      }
    }
  }

  Future<void> _pickImage() async {
    try {
      final XFile? image = await _picker.pickImage(source: ImageSource.gallery);
      if (image != null) {
        setState(() {
          _selectedImage = File(image.path);
        });
        _uploadAvatar();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Gagal memilih gambar: $e')));
      }
    }
  }

  Future<void> _uploadAvatar() async {
    if (_selectedImage == null) return;

    setState(() {
      _isUploadingAvatar = true;
    });

    final success = await context.read<ProfileProvider>().updateAvatar(
      _selectedImage!.path,
    );

    if (mounted) {
      setState(() {
        _isUploadingAvatar = false;
      });
      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Foto profil berhasil diperbarui')),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal memperbarui foto profil')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<ProfileProvider>();
    final user = provider.user;

    return MitologiScaffold(
      title: 'Edit Profil',
      subtitle: 'Perbarui informasi profil Anda',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: provider.isLoading
          ? const EditProfileSkeleton()
          : SingleChildScrollView(
              padding: EdgeInsets.all(
                ResponsiveHelper.horizontalPadding(context),
              ),
              child: FadeInUp(
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Avatar display
                      Center(
                        child: Stack(
                          children: [
                            Container(
                              width: 96,
                              height: 96,
                              decoration: BoxDecoration(
                                color: AppTheme.primary,
                                shape: BoxShape.circle,
                                image: _selectedImage != null
                                    ? DecorationImage(
                                        image: FileImage(_selectedImage!),
                                        fit: BoxFit.cover,
                                      )
                                    : null,
                              ),
                              clipBehavior: Clip.hardEdge,
                              child: _selectedImage == null
                                  ? (user?.avatarUrl != null &&
                                            user!.avatarUrl!.isNotEmpty)
                                        ? CachedNetworkImage(
                                            imageUrl: user.avatarUrl!,
                                            fit: BoxFit.cover,
                                            width: double.infinity,
                                            height: double.infinity,
                                          )
                                        : Center(
                                            child: Text(
                                              user?.name.isNotEmpty == true
                                                  ? user!.name[0].toUpperCase()
                                                  : 'U',
                                              style: const TextStyle(
                                                fontSize: 32,
                                                fontWeight: FontWeight.bold,
                                                color: Colors.white,
                                              ),
                                            ),
                                          )
                                  : null,
                            ),
                            if (_isUploadingAvatar)
                              Container(
                                width: 96,
                                height: 96,
                                decoration: BoxDecoration(
                                  color: Colors.black.withValues(alpha: 0.5),
                                  shape: BoxShape.circle,
                                ),
                                child: const Center(
                                  child: CircularProgressIndicator(
                                    color: Colors.white,
                                  ),
                                ),
                              ),
                            Positioned(
                              bottom: 0,
                              right: 0,
                              child: Container(
                                decoration: BoxDecoration(
                                  color: AppTheme.primary,
                                  shape: BoxShape.circle,
                                  boxShadow: AppTheme.shadowSoft,
                                ),
                                child: IconButton(
                                  tooltip: 'Change photo',
                                  icon: const Icon(
                                    Icons.camera_alt,
                                    color: Colors.white,
                                    size: 20,
                                  ),
                                  onPressed: _isUploadingAvatar
                                      ? null
                                      : _pickImage,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 32),
                      if (provider.error != null)
                        Container(
                          padding: EdgeInsets.all(
                            ResponsiveHelper.horizontalPadding(context),
                          ),
                          margin: const EdgeInsets.only(bottom: 16),
                          decoration: BoxDecoration(
                            color: AppTheme.error.withValues(alpha: 0.1),
                            borderRadius: AppTheme.radius16,
                          ),
                          child: Row(
                            children: [
                              const Icon(
                                Icons.error_outline,
                                color: AppTheme.error,
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: Text(
                                  provider.error!,
                                  style: const TextStyle(color: AppTheme.error),
                                ),
                              ),
                            ],
                          ),
                        ),

                      _buildTextField(
                        label: 'Nama Lengkap',
                        controller: _nameController,
                        required: true,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        label: 'Email',
                        controller: _emailController,
                        required: true,
                        keyboardType: TextInputType.emailAddress,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        label: 'No. WhatsApp',
                        controller: _phoneController,
                        keyboardType: TextInputType.phone,
                      ),
                      const SizedBox(height: 32),

                      SizedBox(
                        width: double.infinity,
                        height: 56,
                        child: ElevatedButton(
                          onPressed: _saveProfile,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.accent,
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            shape: RoundedRectangleBorder(
                              borderRadius: AppTheme.radius16,
                            ),
                            elevation: 0,
                          ),
                          child: const Text(
                            'Simpan Perubahan',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.w700,
                              color: Colors.white,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }

  Widget _buildTextField({
    required String label,
    required TextEditingController controller,
    bool required = false,
    TextInputType? keyboardType,
    int maxLines = 1,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            fontWeight: FontWeight.w600,
            color: AppTheme.onSurfaceVariant,
            fontSize: 14,
          ),
        ),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          keyboardType: keyboardType,
          maxLines: maxLines,
          decoration: InputDecoration(
            hintText: 'Masukkan $label',
            hintStyle: const TextStyle(color: AppTheme.onSurfaceVariant),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: const BorderSide(color: AppTheme.primary, width: 2),
            ),
            filled: true,
            fillColor: AppTheme.surfaceContainerLow,
          ),
          validator: (value) {
            if (required && (value == null || value.isEmpty)) {
              return '$label wajib diisi';
            }
            return null;
          },
        ),
      ],
    );
  }
}
