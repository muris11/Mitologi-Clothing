import 'package:flutter/material.dart';
import '../../config/theme.dart';
import '../../services/tentang_kami_service.dart';
import '../../models/tentang_kami/site_settings_model.dart';
import '../../models/tentang_kami/team_member_model.dart';
import '../../models/tentang_kami/facility_model.dart';
import '../../utils/navigation_helper.dart';
import '../../utils/responsive_helper.dart';
import 'widgets/hero_section.dart';
import 'widgets/about_history_section.dart';
import 'widgets/founder_story_section.dart';
import 'widgets/vision_mission_section.dart';
import 'widgets/production_facilities_section.dart';
import 'widgets/company_legality_section.dart';
import 'widgets/team_structure_section.dart';

class TentangKamiScreen extends StatefulWidget {
  const TentangKamiScreen({super.key});

  @override
  State<TentangKamiScreen> createState() => _TentangKamiScreenState();
}

class _TentangKamiScreenState extends State<TentangKamiScreen> {
  final _service = TentangKamiService();
  SiteSettings? _settings;
  List<TeamMember> _teamMembers = [];
  List<Facility> _facilities = [];
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    try {
      setState(() {
        _isLoading = true;
        _error = null;
      });

      // Try fetching all data from landing-page endpoint first
      final landingData = await _service.getAllData();

      SiteSettings? settings;
      List<TeamMember> members = [];
      List<Facility> facilities = [];

      // Parse site settings
      if (landingData.containsKey('site_settings')) {
        try {
          final siteSettingsData =
              landingData['site_settings'] as Map<String, dynamic>;
          settings = SiteSettings.fromJson(siteSettingsData);
        } on Exception {
          // Error parsing site settings
        }
      }

      // Parse team members
      if (landingData.containsKey('team_members')) {
        try {
          final teamData =
              (landingData['team_members'] as List?) ?? <dynamic>[];
          members = teamData
              .map(
                (m) => TeamMember.fromJson(Map<String, dynamic>.from(m as Map)),
              )
              .toList();
        } on Exception {
          // Error parsing team members
        }
      }

      // Parse facilities
      if (landingData.containsKey('facilities')) {
        try {
          final facilitiesData =
              (landingData['facilities'] as List?) ?? <dynamic>[];
          facilities = facilitiesData
              .map(
                (f) => Facility.fromJson(Map<String, dynamic>.from(f as Map)),
              )
              .toList();
        } on Exception {
          // Error parsing facilities
        }
      }

      // Fallback: try separate endpoints if landing-page doesn't have the data
      if (settings == null) {
        try {
          settings = await _service.getSiteSettings();
        } on Exception {
          // Site settings endpoint failed
        }
      }

      if (members.isEmpty) {
        try {
          members = await _service.getTeamMembers();
        } on Exception {
          // Team members endpoint failed
        }
      }

      if (mounted) {
        setState(() {
          _settings = settings;
          _teamMembers = members;
          _facilities = facilities;
          _isLoading = false;
        });
      }
    } on Exception {
      // Error in _loadData
      if (mounted) {
        setState(() {
          _error = 'Terjadi kesalahan saat memuat data. Silakan coba lagi.';
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        backgroundColor: Colors.white,
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              CircularProgressIndicator(
                color: AppTheme.primary,
                strokeWidth: 3,
              ),
              SizedBox(height: 16),
              Text(
                'Memuat data...',
                style: TextStyle(
                  color: AppTheme.onSurfaceVariant,
                  fontSize: 14,
                ),
              ),
            ],
          ),
        ),
      );
    }

    if (_error != null) {
      return Scaffold(
        backgroundColor: Colors.white,
        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
          leading: IconButton(
            icon: const Icon(
              Icons.arrow_back_ios_new,
              color: AppTheme.primary,
              size: 20,
            ),
            onPressed: () => context.popOrGoHome(),
          ),
          title: const Text(
            'Tentang Kami',
            style: TextStyle(
              color: AppTheme.primary,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        body: Center(
          child: Padding(
            padding: EdgeInsets.all(
              ResponsiveHelper.horizontalPadding(context),
            ),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Container(
                  padding: EdgeInsets.all(
                    ResponsiveHelper.horizontalPadding(context),
                  ),
                  decoration: BoxDecoration(
                    color: AppTheme.error.withValues(alpha: 0.1),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.error_outline,
                    size: 64,
                    color: AppTheme.error,
                  ),
                ),
                const SizedBox(height: 24),
                const Text(
                  'Gagal Memuat Data',
                  style: TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.onSurface,
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  _error!,
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    fontSize: 15,
                    color: AppTheme.onSurfaceVariant,
                    height: 1.5,
                  ),
                ),
                const SizedBox(height: 32),
                SizedBox(
                  width: 200,
                  child: ElevatedButton.icon(
                    onPressed: _loadData,
                    icon: const Icon(Icons.refresh),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primary,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                      elevation: 0,
                    ),
                    label: const Text(
                      'Coba Lagi',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      );
    }

    // Show content even if some data is missing
    final settings = _settings;

    // Parse data for sections - all from API
    final descriptions = [
      settings?.aboutDescription1,
      settings?.aboutDescription2,
    ].where((d) => d != null && d.isNotEmpty).cast<String>().toList();

    final historyParagraphs =
        settings?.aboutShortHistory
            ?.split('\n')
            .map((p) => p.trim())
            .where((p) => p.isNotEmpty)
            .toList() ??
        [];

    final logoMeanings =
        settings?.aboutLogoMeaning
            ?.split('\n')
            .map((m) => m.trim())
            .where((m) => m.isNotEmpty)
            .toList() ??
        [];

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(
            Icons.arrow_back_ios_new,
            color: AppTheme.primary,
            size: 20,
          ),
          onPressed: () => context.popOrGoHome(),
        ),
        title: const Text(
          'Tentang Kami',
          style: TextStyle(
            color: AppTheme.primary,
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              margin: const EdgeInsets.fromLTRB(20, 20, 20, 0),
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppTheme.surfaceContainerLowest,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppTheme.outlineLight),
              ),
              child: const Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Icon(
                    Icons.auto_stories_outlined,
                    color: AppTheme.primary,
                    size: 18,
                  ),
                  SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      'Halaman ini merangkum cerita, nilai, tim, dan fondasi Mitologi agar lebih mudah dipahami dalam sekali lihat.',
                      style: TextStyle(
                        color: AppTheme.onSurfaceVariant,
                        fontSize: 12,
                        height: 1.45,
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // 1. Hero Section - data dari API
            HeroSection(
              title: settings?.siteName?.isNotEmpty ?? false
                  ? 'Tentang ${settings?.siteName}'
                  : null,
              subtitle: settings?.aboutHeadline ?? settings?.siteTagline,
            ),

            // 2. About History Section - data dari API
            AboutHistorySection(
              siteName: settings?.siteName,
              foundedYear: settings?.companyFoundedYear,
              descriptions: descriptions,
              historyParagraphs: historyParagraphs,
              logoMeanings: logoMeanings,
              aboutImage: settings?.aboutImage,
            ),

            // 3. Founder Story Section - tampil jika ada data founder dari API
            if ((settings?.founderName?.isNotEmpty ?? false) ||
                (settings?.founderPhoto?.isNotEmpty ?? false))
              FounderStorySection(
                founderName: settings?.founderName ?? '',
                founderRole: settings?.founderRole,
                founderImageUrl: settings?.founderPhoto,
                story: settings?.founderStory,
              ),

            // 4. Vision Mission Section - data dari API (tampil jika ada visi, misi, atau nilai perusahaan)
            if ((settings?.visionText?.isNotEmpty ?? false) ||
                (settings?.missionText?.isNotEmpty ?? false) ||
                (settings?.companyValues?.isNotEmpty ?? false))
              VisionMissionSection(
                vision: settings?.visionText ?? '',
                missionText: settings?.missionText,
                companyValues: settings?.companyValues ?? [],
              ),

            // 5. Production Facilities Section - data dari API
            if (_facilities.isNotEmpty)
              ProductionFacilitiesSection(facilities: _facilities),

            // 6. Company Legality Section - data dari API
            if (settings?.legality != null)
              CompanyLegalitySection(legality: settings?.legality),

            // 7. Team Structure Section - data dari API
            if (_teamMembers.isNotEmpty)
              TeamStructureSection(teamMembers: _teamMembers),

            const SizedBox(height: 48),
          ],
        ),
      ),
    );
  }
}
